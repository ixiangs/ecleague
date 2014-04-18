<?php
namespace Core\Attrs\Backend;

use Toy\Data\Helper;
use Toy\Util\ArrayUtil;
use Toy\Web;

class AttributeController extends Web\Controller
{

    public function listAction()
    {
        $pi = $this->request->getParameter("pageindex", 1);
        $count = \Tops::loadModel('attrs/attribute')->find()->selectCount()->execute()->getFirstValue();
        $models = \Tops::loadModel('attrs/attribute')->find()
            ->asc('name')
            ->limit(PAGINATION_SIZE, ($pi - 1) * PAGINATION_SIZE)
            ->load();
        return Web\Result::templateResult(array(
                'models' => $models,
                'total' => $count,
                'pageIndex' => $pi)
        );
    }

    public function typeAction()
    {
        return Web\Result::templateResult();
    }

    public function addAction()
    {
        $model = \Tops::loadModel('attrs/attribute')->fillArray(array(
            'data_type' => $this->request->getQuery('data_type'),
            'input_type' => $this->request->getQuery('input_type')
        ));
        return $this->getEditTemplateResult($model);
    }

    public function addPostAction()
    {
        $locale = $this->context->locale;
        $m = \Tops::loadModel('attrs/attribute')->fillArray($this->request->getPost('data'));

        $vr = $m->validateProperties();
        if ($vr !== true) {
            $this->session->set('errors', $locale->_('err_input_invalid'));
            return $this->getEditTemplateResult($m);
        }

        if (!$m->validateUnique()) {
            $this->session->set('errors', $locale->_('attrs_err_attribute_exists', $m->getCode()));
            return $this->getEditTemplateResult($m);
        }


        if (!$m->insert()) {
            $this->session->set('errors', $locale->_('err_system'));
            return $this->getEditTemplateResult($m);
        }

        return Web\Result::redirectResult($this->router->buildUrl('list'));
    }

    public function editAction($id)
    {
        $m = \Tops::loadModel('attrs/attribute');
        $m->load($id);
        return $this->getEditTemplateResult($m);
    }

    public function editPostAction()
    {
        $locale = $this->context->locale;
        $m = \Tops::loadModel('attrs/attribute')->fillArray($this->request->getPost('data'));

        $vr = $m->validateProperties();
        if ($vr !== true) {
            $this->session->set('errors', $locale->_('err_input_invalid'));
            return $this->getEditTemplateResult($m);
        }

        if (!$m->update()) {
            $this->session->set('errors', $locale->_('err_system'));
            return $this->getEditTemplateResult($m);
        }

        return Web\Result::redirectResult($this->router->buildUrl('list'));
    }

    public function deleteAction($id)
    {
        $lang = $this->context->locale;
        $m = \Tops::loadModel('attrs/attribute')->load($id);

        if (!$m) {
            $this->session->set('errors', $lang->_('err_system'));
            return Web\Result::redirectResultt($this->router->buildUrl('list'));
        }

        if (!$m->delete()) {
            $this->session->set('errors', $lang->_('err_system'));
            return Web\Result::redirectResult($this->router->buildUrl('list'));
        }
        return Web\Result::redirectResult($this->router->buildUrl('list'));
    }

    public function optionsAction($attributeid)
    {
        $attr = \Tops::loadModel('attrs/attribute')->load($attributeid);
        $options = $attr->getOptions()->load();
        return Web\Result::templateResult(array(
            'attribute' => $attr,
            'options' => $options
        ));
    }

    public function optionsPostAction($attributeid)
    {
        $lang = $this->context->locale;
        $attr = \Tops::loadModel('attrs/attribute')->load($this->request->getPost('attribute_id'));
        $options = ArrayUtil::toArray($this->request->getPost('options'), function($item) use($attr, $ao){
            return \Tops::loadModel('attrs/attributeOption')
                        ->fillArray($item)
                        ->setAttributeId($attr->getId());
        });

        //check unique
        $values = ArrayUtil::toArray($options, function($item){
            return $item->getValue();
        });
        $repeated = ArrayUtil::contains(array_count_values($values), function($item){
            return $item > 1;
        });
        if($repeated){
            $this->session->set('errors', $lang->_('attrs_err_option_repeated'));
            return Web\Result::templateResult(array(
                'attribute' => $attr,
                'options' => $options
            ));
        }

        $res = Helper::withTx(function($db) use($attr, $options, $lang){
            $dids = $this->request->getPost('delete_ids');
            if($dids){
                \Tops::loadModel('attrs/attributeOption')->deleteBatch($dids, $db);
            }
            foreach($options as $option){
                $b = $option->getId()? $option->update($db): $option->insert($db);
                if(!$b){
                    return false;
                }
            }
            return true;
        });

        if($res){
            return Web\Result::redirectResult($this->router->buildUrl('list'));
        }else{
            $this->session->set('errors', $lang->_('err_system'));
            return Web\Result::templateResult(array(
                'attribute' => $attr,
                'options' => $options
            ));
        }
    }

    private function getEditTemplateResult($model)
    {
        return Web\Result::templateResult(
            array('model' => $model),
            'attrs/attribute/edit'
        );
    }
}