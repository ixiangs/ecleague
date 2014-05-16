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
                    ->join(System\Constant::TABLE_COMPONENT,
                           System\Constant::TABLE_COMPONENT.'.id',
                           Constant::TABLE_BEHAVIOR.'.component_id')
                    ->limit(PAGINATION_SIZE, ($pi - 1) * PAGINATION_SIZE)->load();
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
        $m = new BehaviorModel($this->request->getPost('data'));
        $vr = $m->validateProperties();
        if ($vr !== true) {
            $this->session->set('errors', $lang->_('err_input_invalid'));
            return $this->getEditTemplateResult($m);
        }

        if($m->getId()){
            if (!$m->update()) {
                $this->session->set('errors', $lang->_('err_system'));
                return $this->getEditTemplateResult($m);
            }
        }else{
            $vr = $m->checkUnique();
            if ($vr !== true) {
                $this->session->set('errors', $lang->_('err_code_exists', $m->getCode()));
                return $this->getEditTemplateResult($m);
            }
            if (!$m->insert()) {
                $this->session->set('errors', $lang->_('err_system'));
                return $this->getEditTemplateResult($m);
            }

        }

        return Web\Result::redirectResult($this->router->buildUrl('list'));
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