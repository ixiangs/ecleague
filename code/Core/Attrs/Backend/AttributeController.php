<?php
namespace Core\Attrs\Backend;

use Ecleague\Tops;
use Toy\Data\Helper;
use Toy\Util\ArrayUtil;
use Toy\Web;

class AttributeController extends Web\Controller
{

    public function listAction()
    {
        $pi = $this->request->getParameter("pageindex", 1);
        $count = Tops::loadModel('attrs/attribute')->find()->selectCount()->execute()->getFirstValue();
        $models = Tops::loadModel('attrs/attribute')->find()
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
        $model = Tops::loadModel('attrs/attribute')->fillArray(array(
            'data_type' => $this->request->getQuery('data_type'),
            'input_type' => $this->request->getQuery('input_type'),
            'component_id' => $this->request->getQuery('component_id')
        ));
        return $this->getEditTemplateResult($model);
    }

    public function editAction($id)
    {
        $m = Tops::loadModel('attrs/attribute')->load($id);
        return $this->getEditTemplateResult($m);
    }

    public function savePostAction()
    {
        $locale = $this->context->locale;
        $m = Tops::loadModel('attrs/attribute')->fillArray($this->request->getPost('data'));

        $vr = $m->validateProperties();
        if ($vr !== true) {
            $this->session->set('errors', $locale->_('err_input_invalid'));
            return $this->getEditTemplateResult($m);
        }

        if ($this->request->getPost('action') == 'add') {
            if (!$m->insert()) {
                $this->session->set('errors', $locale->_('err_system'));
                return $this->getEditTemplateResult($m);
            }
        } else {
            if (!$m->update()) {
                $this->session->set('errors', $locale->_('err_system'));
                return $this->getEditTemplateResult($m);
            }
        }

        if ($this->request->hasParameter('set_id')) {
            return Web\Result::redirectResult($this->router->buildUrl(
                'attribute-set/groups', array('id' => $this->request->getQuery('set_id'))));
        }

        return Web\Result::redirectResult($this->router->buildUrl('list'));
    }



//    public function editPostAction()
//    {
//        $locale = $this->context->locale;
//        $m = Tops::loadModel('attrs/attribute')->fillArray($this->request->getPost('data'));
//
//        $vr = $m->validateProperties();
//        if ($vr !== true) {
//            $this->session->set('errors', $locale->_('err_input_invalid'));
//            return $this->getEditTemplateResult($m);
//        }
//
//        if (!$m->update()) {
//            $this->session->set('errors', $locale->_('err_system'));
//            return $this->getEditTemplateResult($m);
//        }
//
//        return Web\Result::redirectResult($this->router->buildUrl('list'));
//    }

    public function deleteAction($id)
    {
        $lang = $this->context->locale;
        $m = Tops::loadModel('attrs/attribute')->load($id);

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
        $attr = Tops::loadModel('attrs/attribute')->load($attributeid);
        $options = $attr->getOptions();
        return Web\Result::templateResult(array(
            'attribute' => $attr,
            'options' => $options
        ));
    }

    public function optionsPostAction($attributeid)
    {
        $lang = $this->context->locale;
        $attr = Tops::loadModel('attrs/attribute')->load($this->request->getPost('attribute_id'));
        $options = $this->request->getPost('options');
        //check unique
        $repeated = ArrayUtil::contains(array_count_values(
            ArrayUtil::toArray($options, function ($item) {
                return array($item['value'], null);
            })
        ), function ($item) {
            return $item > 1;
        });
        if ($repeated) {
            $this->session->set('errors', $lang->_('attrs_err_option_repeated'));
            return Web\Result::templateResult(array(
                'attribute' => $attr,
                'options' => $options
            ));
        }

        if ($attr->setOptions($options)->update()) {
            return Web\Result::redirectResult($this->router->buildUrl('list'));
        } else {
            $this->session->set('errors', $lang->_('err_system'));
            return Web\Result::templateResult(array(
                'attribute' => $attr,
                'options' => $options
            ));
        }
    }

    private function getEditTemplateResult($model)
    {
        $c = Tops::loadModel('admin/component')->load($model->getComponentId());
        return Web\Result::templateResult(
            array('model' => $model, 'component' => $c),
            'attrs/attribute/edit'
        );
    }
}