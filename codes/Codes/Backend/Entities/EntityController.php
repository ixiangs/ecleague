<?php
namespace Ixiangs\Entities;

use Ixiangs\System\ComponentModel;
use Toy\Db\Helper;
use Toy\Html\Document;
use Toy\Web;

class EntityController extends Web\Controller
{

    public function listAction()
    {
        $pi = $this->request->getParameter("pageindex", 1);
        $count = EntityModel::find()->executeCount();
        $models = EntityModel::find()
            ->limit(PAGINATION_SIZE, ($pi - 1) * PAGINATION_SIZE)
            ->load();
        return Web\Result::templateResult(array(
                'models' => $models,
                'total' => $count,
                'pageIndex' => $pi)
        );
    }

    public function addAction()
    {
        return $this->getEditTemplateResult(EntityModel::create());
    }

    public function editAction($id)
    {
        return $this->getEditTemplateResult(EntityModel::load($id));
    }

    public function savePostAction()
    {
        $locale = $this->context->localize;
        $data = $this->request->getPost('data');
        $model = $data['id'] ? EntityModel::merge($data['id'], $data) : EntityModel::create($data);

        $vr = $model->validate();
        if ($vr !== true) {
            $this->session->set('errors', $locale->_('err_input_invalid'));
            return $this->getEditTemplateResult($model);
        }

        if (!$model->save()) {
            $this->session->set('errors', $locale->_('err_system'));
            return $this->getEditTemplateResult($model);
        }

        return Web\Result::redirectResult($this->router->buildUrl('list'));
    }

    public function deleteAction($id)
    {
        $lang = $this->context->localize;
        $m = EntityModel::load($id);

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

    public function groupsGetAction($entityid)
    {
        $entity = EntityModel::load($entityid);
        $groups = GroupModel::find()
            ->eq('entity_id', $entity->getId())
            ->load();
        $fields = FieldModel::find()
            ->eq('entity_id', $entity->getId())
            ->load();
        Document::singleton()->addBreadcrumbs($entity->getName(), $this->router->buildUrl('list'));
        return Web\Result::templateResult(
            array('entity' => $entity,
                'groups' => $groups,
                'fields' => $fields)
        );
    }

    public function saveGroupAjaxPostAction()
    {
        $locale = $this->context->localize;
        $data = $this->request->getPost('data');
        $model = $data['id'] ? GroupModel::merge($data['id'], $data) : GroupModel::create($data);

        $vr = $model->validate();
        if ($vr !== true) {
            $this->session->set('errors', $locale->_('err_input_invalid'));
            return Web\Result::jsonResult(array(
                'success'=>'0',
                'message'=>$locale->_('err_input_invalid')
            ));
        }

        if (!$model->save()) {
            return Web\Result::jsonResult(array(
                'success'=>'0',
                'message'=>$locale->_('err_system')
            ));
        }

        return Web\Result::jsonResult(array(
           'success'=>'1',
           'data'=>array(
               'id'=>$model->getId(),
               'name'=>$model->getName()
           )
        ));
    }


    private function getEditTemplateResult($model)
    {
        $components = ComponentModel::find()
            ->execute()
            ->combineColumns('id', 'name');
        return Web\Result::templateResult(
            array('model' => $model, 'components' => $components),
            'ixiangs/entities/entity/edit'
        );
    }
}