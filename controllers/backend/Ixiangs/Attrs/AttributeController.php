<?php
namespace Ixiangs\Attrs;

use Ixiangs\Attrs\Model\AttributeModel;
use Ecleague\Tops;
use Toy\Db\Helper;
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
        $components = Tops::loadModel('admin/component')
                        ->find()->load()
                        ->toArray(function($item){
                            return array($item->getId(), $item->getName());
                        });
        return Web\Result::templateResult(array(
            'components'=>$components
        ));
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

        if ($m->getId()) {
            if (!$m->merge()->update()) {
                $this->session->set('errors', $locale->_('err_system'));
                return $this->getEditTemplateResult($m);
            }
        } else {
            if (!$m->insert()) {
                $this->session->set('errors', $locale->_('err_system'));
                return $this->getEditTemplateResult($m);
            }
        }

        if($this->request->getPost('next_action') == 'new'){
            return Web\Result::redirectResult($this->router->buildUrl('type'));
        }

        return Web\Result::redirectResult($this->router->buildUrl('list'));
    }

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

//    public function optionsAction($id)
//    {
//        $attr = Tops::loadModel('attrs/attribute')->load($id);
//        $options = $attr->getOptions();
//        return Web\Result::templateResult(array(
//            'attribute' => $attr,
//            'options' => $options
//        ));
//    }
//
//    public function optionsPostAction($id)
//    {
//        $lang = $this->context->locale;
//        $attr = Tops::loadModel('attrs/attribute')->load($this->request->getPost('attribute_id'));
//        $options = $this->request->getPost('options');
//        //check unique
//        $repeated = ArrayUtil::contains(array_count_values(
//            ArrayUtil::toArray($options, function ($item) {
//                return array($item['value'], null);
//            })
//        ), function ($item) {
//            return $item > 1;
//        });
//        if ($repeated) {
//            $this->session->set('errors', $lang->_('attrs_err_option_repeated'));
//            return Web\Result::templateResult(array(
//                'attribute' => $attr,
//                'options' => $options
//            ));
//        }
//
//        if ($attr->setOptions($options)->update()) {
//            if ($this->request->hasParameter('set_id')) {
//                return Web\Result::redirectResult($this->router->buildUrl(
//                    'attribute-set/groups', array('id' => $this->request->getQuery('set_id'))));
//            }
//
//            return Web\Result::redirectResult($this->router->buildUrl('list'));
//        } else {
//            $this->session->set('errors', $lang->_('err_system'));
//            return Web\Result::templateResult(array(
//                'attribute' => $attr,
//                'options' => $options
//            ));
//        }
//    }

    private function getEditTemplateResult($model)
    {
        $c = Tops::loadModel('admin/component')->load($model->getComponentId());
        return Web\Result::templateResult(
            array('model' => $model, 'component' => $c),
            'attrs/attribute/edit'
        );
    }
}