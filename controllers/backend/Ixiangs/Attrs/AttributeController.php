<?php
namespace Ixiangs\Attrs;

use Ixiangs\System\ComponentModel;
use Toy\Db\Helper;
use Toy\Web;

class AttributeController extends Web\Controller
{

    public function listAction()
    {
        $pi = $this->request->getParameter("pageindex", 1);
        $count = AttributeModel::find()->executeCount();
        $models = AttributeModel::find()
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
        $components = ComponentModel::find()->load()
            ->toArray(function ($item) {
                return array($item->getId(), $item->getName());
            });
        return Web\Result::templateResult(array(
            'components' => $components
        ));
    }

    public function addAction()
    {
        $model = new AttributeModel(array(
            'data_type' => $this->request->getQuery('data_type'),
            'input_type' => $this->request->getQuery('input_type'),
            'component_id' => $this->request->getQuery('component_id')
        ));
        return $this->getEditTemplateResult($model);
    }

    public function editAction($id)
    {
        $model = AttributeModel::load($id);
        $model->getOptions()->load();
        return $this->getEditTemplateResult($model);
    }

    public function savePostAction()
    {
        $locale = $this->context->locale;
        $data = $this->request->getPost('data');
        list($newOptions, $editOptions, $deleteOptions) = $this->request->listPost('new_options', 'edit_options', 'delete_options');
        if ($data['id']) {
            $model = AttributeModel::merge($data['id'], $data);
            $options = $model->getOptions()->load();
        } else {
            $model = AttributeModel::create($data);
            $options = $model->getOptions();
        }

        foreach ($options as $option) {
            $optionId = $option->getId();
            if (is_array($deleteOptions) && in_array($optionId, $deleteOptions)) {
                $option->markDeleted();
            } elseif (is_array($editOptions) && array_key_exists($optionId, $editOptions)) {
                $option->setAllData($editOptions[$optionId]);
            }
        }

        if (is_array($newOptions)) {
            foreach ($newOptions as $newOption) {
                $options->append(OptionModel::create($newOption)->setAttributeId($model->getId()));
            }
        }

        $validated = $model->validate();
        if ($validated !== true) {
            $this->session->set('errors', $locale->_('err_input_invalid'));
            return $this->getEditTemplateResult($model);
        }

        $success = Helper::withTx(function ($tx) use ($model) {
            if(!$model->save($tx)){
                $tx->rollback();
                return false;
            }
            return true;
        });

        if ($success) {
            return $this->request->getPost('next_action') == 'new' ?
                Web\Result::redirectResult($this->router->buildUrl('type')) :
                Web\Result::redirectResult($this->router->buildUrl('list'));
        } else {
            $this->session->set('errors', $locale->_('err_system'));
            return $this->getEditTemplateResult($model);
        }
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
        $c = ComponentModel::load($model->getComponentId());
        return Web\Result::templateResult(
            array('model' => $model, 'component' => $c),
            'ixiangs/attrs/attribute/edit'
        );
    }
}