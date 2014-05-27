<?php
namespace Ixiangs\User;

use Ixiangs\System;
use Toy\Web;

class BehaviorController extends Web\Controller
{

    public function listAction()
    {
        $pi = $this->request->getParameter("pageindex", 1);
        $count = BehaviorModel::find()->executeCount();
        $models = BehaviorModel::find()
                    ->select(System\Constant::TABLE_COMPONENT.'.name as component_name', Constant::TABLE_BEHAVIOR.'.*')
                    ->select(System\ComponentModel::propertyToField('name', 'component_name'))
                    ->join(System\ComponentModel::propertyToField('id'), BehaviorModel::propertyToField('component_id'))
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
        return $this->getEditTemplateResult(new BehaviorModel());
    }

    public function editAction($id)
    {
        return $this->getEditTemplateResult(BehaviorModel::load($id));
    }

    public function savePostAction()
    {
        $lang = $this->context->locale;
        $data = $this->request->getPost('data');
        $model = $data['id'] ? BehaviorModel::merge($data['id'], $data) : RoleModel::create($data);

        $vr = $model->validateProperties();
        if ($vr !== true) {
            $this->session->set('errors', $this->_('err_input_invalid'));
            return $this->getEditTemplateReult($model);
        }

        if ($model->isNewed()) {
            $vr = $model->checkUnique();
            if ($vr !== true) {
                $this->session->set('errors', $lang->_('err_code_exists', $model->getCode()));
                return $this->getEditTemplateReult($model);
            }
        }

        if (!$model->save()) {
            $this->session->set('errors', $this->_('err_system'));
            return $this->getEditTemplateReult($model);;
        }

        return Web\Result::redirectResult($this->router->getHistoryUrl('list'));
    }

    public function deleteAction($id)
    {
        $lang = $this->context->locale;
        $m = BehaviorModel::load($id);

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

    private function getEditTemplateResult($model)
    {
        $components = System\ComponentModel::find()->load()->toArray(function($item){
           return array($item->getId(), $item->getName());
        });
        return Web\Result::templateResult(
            array('model' => $model, 'components'=>$components),
            'ixiangs/user/behavior/edit'
        );
    }
}