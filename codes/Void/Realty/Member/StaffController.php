<?php
namespace Void\Realty\Member;

use Void\Auth;
use Toy\Web;
use Void\Realty\StaffModel;

class StaffController extends Web\Controller
{

    public function listAction()
    {
        $pi = $this->request->getParameter("pageindex", 1);
        $count = StaffModel::find()
            ->eq('uptown_id', $this->session->get('uptownId'))
            ->eq('deleted', 0)
            ->fetchCount();
        $models = StaffModel::find()
            ->eq('uptown_id', $this->session->get('uptownId'))
            ->eq('deleted', 0)
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
        return $this->getEditTemplateResult(new StaffModel());
    }

    public function addPostAction()
    {
        return $this->save();
    }

    public function editAction($id)
    {
        return $this->getEditTemplateResult(StaffModel::load($id));
    }

    public function editPostAction()
    {
        return $this->save();
    }

    private function save()
    {
        $data = $this->request->getPost('data');
        $model = $data['id'] ?
            StaffModel::merge($data['id'], $data) :
            StaffModel::create($data);
        $model->setUptownId($this->session->get('uptownId'));
        $vr = $model->validate();
        if ($vr !== true) {
            $this->session->set('errors', $this->localize->_('err_input_invalid'));
            return $this->getEditTemplateResult($model);
        }

        if($model->isNewed()){
            $model->setUptownId($this->session->get('uptownId'));
        }

        if (!$model->save()) {
            $this->session->set('errors', $this->localize->_('err_system'));
            return $this->getEditTemplateResult($model);
        }
        return Web\Result::redirectResult($this->router->findHistory('list'));
    }

    public function deleteAction($id)
    {
        $m = StaffModel::load($id);

        if (!$m) {
            $this->session->set('errors', $this->localize->_('err_system'));
            return Web\Result::redirectResultt($this->router->buildUrl('list'));
        }

        if (!$m->delete()) {
            $this->session->set('errors', $this->localize->_('err_system'));
            return Web\Result::redirectResult($this->router->buildUrl('list'));
        }
        return Web\Result::redirectResult($this->router->buildUrl('list'));
    }

    private function getEditTemplateResult($model)
    {
        return Web\Result::templateResult(
            array('model' => $model),
            'edit'
        );
    }
}