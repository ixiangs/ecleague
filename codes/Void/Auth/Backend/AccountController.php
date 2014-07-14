<?php
namespace Void\Auth\Backend;

use Void\Auth\AccountModel;
use Void\Auth\GroupModel;
use Void\Auth\RoleModel;
use Toy\Web;

class AccountController extends Web\Controller
{

    public function listAction()
    {
        $pi = $this->request->getParameter("pageindex", 1);
        $count = AccountModel::find()->fetchCount();
        $models = AccountModel::find()
            ->select(GroupModel::propertyToField('name', 'group_name'))
            ->join(GroupModel::propertyToField('id'), AccountModel::propertyToField('group_id'))
            ->limit(PAGINATION_SIZE, ($pi - 1) * PAGINATION_SIZE)
            ->load();
        return Web\Result::TemplateResult(array(
                'models' => $models,
                'roles' => RoleModel::find()->fetch()->combineColumns('id', 'code'),
                'total' => $count,
                'pageIndex' => $pi)
        );
    }

    public function addAction()
    {
        return $this->getEditTemplateResult(AccountModel::create());
    }

    public function editAction($id)
    {
        return $this->getEditTemplateResult(AccountModel::load($id));
    }

    public function savePostAction()
    {
        $lang = $this->context->localize;
        $data = $this->request->getPost('data');
        $model = $data['id'] ?
            AccountModel::merge($data['id'], $data) :
            AccountModel::create($data);
        if($data['group_id'] == 1){//Adminstrator
            $model->setRoleIds('*')->setDomains('*');
        }
        $vr = $model->validate();
        if ($vr !== true) {
            $this->session->set('errors', $lang->_('err_input_invalid'));
            return $this->getEditTemplateResult($model);
        }

        if ($model->isNew()) {
            $vr = $model->checkUnique();
            if ($vr !== true) {
                $this->session->set('errors', $lang->_('user_err_account_exists', $model->getCode()));
                return $this->getEditTemplateResult($model);
            }
        }

        if (!$model->save()) {
            $this->session->set('errors', $lang->_('err_system'));
            return $this->getEditTemplateResult($model);
        }

        return Web\Result::RedirectResult($this->router->findHistory('list'));
    }

    public function deleteAction($id)
    {
        $model = AccountModel::load($id);

        if ($model) {
            if (!$model->delete()) {
                $this->session->set('errors', $this->languages->get('err_system'));
            }
        } else {
            $this->session->set('errors', $this->languages->get('err_system'));
        }


        return Web\Result::RedirectResult($this->router->findHistory('list'));
    }

    private function getEditTemplateResult($model)
    {
        return Web\Result::TemplateResult(
            array(
                'model' => $model,
                'roles' => RoleModel::find()->fetch()->combineColumns('id', 'name'),
                'groups' => GroupModel::find()->fetch()->combineColumns('id', 'name')
            ),
            'void/auth/account/edit'
        );
    }
}