<?php
namespace Void\Realty\Member;

use Void\Realty\BuildingModel;
use Void\Auth;
use Toy\Web;

class BuildingController extends Web\Controller
{

    public function listAction()
    {
        $pi = $this->request->getParameter("pageindex", 1);
        $count = BuildingModel::find()->fetchCount();
        $models = BuildingModel::find()
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
        return $this->getEditTemplateResult(new BuildingModel());
    }

    public function addPostAction()
    {
        return $this->save();
    }

    public function editAction($id)
    {
        return $this->getEditTemplateResult(BuildingModel::load($id));
    }

    public function editPostAction()
    {
        return $this->save();
    }

    private function save()
    {
        $data = $this->request->getPost('data');
        $model = $data['id'] ?
            BuildingModel::merge($data['id'], $data) :
            BuildingModel::create($data);
        $model->setUptownId($this->session->get('uptownId'));
        $vr = $model->validate();
        if ($vr !== true) {
            $this->session->set('errors', $this->localize->_('err_input_invalid'));
            return $this->getEditTemplateResult($model);
        }

        if (!$model->save()) {
            $this->session->set('errors', $this->localize->_('err_system'));
            return $this->getEditTemplateResult($model);
        }
        return Web\Result::redirectResult($this->router->findHistory('list'));
    }

    public function deleteAction($id)
    {
        $m = BuildingModel::load($id);

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