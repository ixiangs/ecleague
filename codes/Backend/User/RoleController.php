<?php
namespace Ixiangs\User;

use Toy\Web;

class RoleController extends Web\Controller
{

    public function listAction()
    {
        $pi = $this->request->getParameter("pageindex", 1);
        $count = RoleModel::find()->executeCount();
        $models = RoleModel::find()->limit(PAGINATION_SIZE, ($pi - 1) * PAGINATION_SIZE)->load();
        return Web\Result::templateResult(array(
                'models' => $models,
                'behaviors' => BehaviorModel::find()->execute()->combineColumns('id', 'code'),
                'total' => $count)
        );
    }

    public function addAction()
    {
        return $this->getEditTemplateReult(new RoleModel());
    }

    public function editAction($id)
    {
        return $this->getEditTemplateReult(RoleModel::load($id));
    }

    public function savePostAction()
    {
        $lang = $this->context->locale;
        $data = $this->request->getPost('data');
        $model = $data['id'] ? RoleModel::merge($data['id'], $data) : RoleModel::create($data);

        $vr = $model->validate();
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

        return Web\Result::redirectResult($this->router->findHistory('list'));
    }

    public function deleteAction($id)
    {
        $m = RoleModel::load($id);

        if (!$m) {
            $this->session->set('errors', $this->languages->get('err_system'));
            return Web\Result::redirectResult($this->router->buildUrl('index'));
        }

        if (!$m->delete()) {
            $this->session->set('errors', $this->languages->get('err_system'));
            return Web\Result::redirectResultt($this->router->buildUrl('index'));
        }
        return Web\Result::redirectResult($this->router->buildUrl('list'));
    }

    private function getEditTemplateReult($model)
    {
        return Web\Result::templateResult(
            array(
                'model' => $model,
                'behaviors' => BehaviorModel::find()->asc('code')->execute()->combineColumns('id', 'name')),
            'ixiangs/user/role/edit'
        );
    }
}