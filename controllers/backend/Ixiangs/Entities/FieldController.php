<?php
namespace Ixiangs\Entities;

use Ixiangs\System\ComponentModel;
use Toy\Db\Helper;
use Toy\View\Html\Document;
use Toy\Web;

class FieldController extends Web\Controller
{

    public function listAction($entityid)
    {
        $fields = FieldModel::find()
            ->eq('entity_id', $entityid)
            ->load();
        $entity = EntityModel::load($entityid);
        Document::singleton()->addBreadcrumbs($entity->getName(), $this->router->buildUrl('entity/list'));
        return Web\Result::templateResult(array(
            'models' => $fields
        ));
    }

    public function addAction($entityid)
    {
        $entity = EntityModel::load($entityid);
        $model = FieldModel::create(array(
            'component_id' => $entity->getComponentId(),
            'entity_id' => $entity->getId()
        ));
        return $this->getEditTemplateResult($model);
    }

    public function editAction($id)
    {
        $model = FieldModel::load($id);
        return $this->getEditTemplateResult($model);
    }

    public function savePostAction()
    {
        $locale = $this->context->locale;
        $data = $this->request->getPost('data');
        $model = $data['id'] ? FieldModel::merge($data['id'], $data) : FieldModel::create($data);

        $vr = $model->validateProperties();
        if ($vr !== true) {
            $this->session->set('errors', $locale->_('err_input_invalid'));
            return $this->getEditTemplateResult($model);
        }

        if (!$model->save()) {
            $this->session->set('errors', $locale->_('err_system'));
            return $this->getEditTemplateResult($model);
        }

        return Web\Result::redirectResult($this->router->buildUrl('list', array('entityid' => $model->getEntityId())));
    }

    public function deleteAction($id)
    {
        $lang = $this->context->locale;
        $model = FieldModel::load($id);

        if ($model) {
            if (!$model->delete()) {
                $this->session->set('errors', $lang->_('err_system'));
            }
        } else {
            $this->session->set('errors', $lang->_('err_system'));
        }

        return Web\Result::redirectResult($this->router->getHistoryUrl('list', array('entityid' => $model->getEntityId())));
    }

    private function getEditTemplateResult($model)
    {
        $locale = $this->context->locale;
        $entity = EntityModel::load($model->getEntityId());
        Document::singleton()->addBreadcrumbs($entity->getName(), $this->router->getHistoryUrl('list'));
        $attributes = AttributeModel::find()
            ->select(ComponentModel::propertyToField('name', 'component_name'))
            ->join(ComponentModel::propertyToField('id'), AttributeModel::propertyToField('component_id'), 'left')
            ->load()
            ->toArray(function ($item) use($locale) {
                $name =  $item->getName();
                $name .= $item->component_name? '('.$item->component_name.')': '('.$locale->_('entities_common').')';
                return array($item->getId(), $name);
            });
        return Web\Result::templateResult(
            array('model' => $model, 'attributes' => $attributes),
            'ixiangs/entities/field/edit'
        );
    }
}