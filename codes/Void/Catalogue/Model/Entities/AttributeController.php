<?php
namespace Ixiangs\Entities;

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
            ->select(ComponentModel::propertyToField('name') . ' AS component_name')
            ->join(ComponentModel::propertyToField('id'), AttributeModel::propertyToField('component_id'), 'left')
            ->asc(AttributeModel::propertyToField('component_id'))
            ->limit(PAGINATION_SIZE, ($pi - 1) * PAGINATION_SIZE)
            ->load();
        return Web\Result::templateResult(array(
                'models' => $models,
                'total' => $count,
                'pageIndex' => $pi)
        );
    }

    public function chooseAction()
    {
        $components = ComponentModel::find()->load()
            ->toArray(function ($item) {
                return array($item->getId(), $item->getName());
            });
        $components[0] = $this->context->localize->_('entities_common');
        ksort($components);
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
        $locale = $this->context->localize;
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
            if (!$model->save($tx)) {
                $tx->rollback();
                return false;
            }
            return true;
        });

        if ($success) {
            return Web\Result::redirectResult($this->router->findHistory('list'));
        } else {
            $this->session->set('errors', $locale->_('err_system'));
            return $this->getEditTemplateResult($model);
        }
    }

    public function deleteAction($id)
    {
        $lang = $this->context->localize;
        $model = AttributeModel::load($id);
        $result = Helper::withTx(function ($tx) use ($model) {
            if (!$model->delete($tx)) {
                return false;
            }
            return true;
        });
        if (!$result) {
            $this->session->set('errors', $lang->_('err_system'));
        }
        return Web\Result::redirectResult($this->router->findHistory('list'));
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
//        $lang = $this->context->localize;
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
//            $this->session->set('errors', $lang->_('entities_err_option_repeated'));
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
        $component = null;
        if (!empty($this->component_id)) {
            $component = ComponentModel::load($model->getComponentId());
        }

        return Web\Result::templateResult(
            array('model' => $model, 'component' => $component),
            'ixiangs/entities/attribute/edit'
        );
    }
}