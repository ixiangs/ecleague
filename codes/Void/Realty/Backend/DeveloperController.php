<?php
namespace Void\Realty\Backend;

use Void\Realty\DeveloperModel;
use Toy\Web;

class DeveloperController extends Web\Controller
{

    public function listAction()
    {
        $pi = $this->request->getParameter("pageindex", 1);
        $count = DeveloperModel::find()->fetchCount();
        $models = DeveloperModel::find()
            ->asc('id')
            ->limit(PAGINATION_SIZE, ($pi - 1) * PAGINATION_SIZE)
            ->load();
        return Web\Result::TemplateResult(array(
                'models' => $models,
                'total' => $count,
                'pageIndex' => $pi)
        );
    }

    public function addAction()
    {
        return $this->getEditTemplateResult(DeveloperModel::create());
    }

    public function editAction($id)
    {
        return $this->getEditTemplateResult(DeveloperModel::load($id));
    }

    public function savePostAction()
    {
        $lang = $this->context->localize;
        $data = $this->request->getPost('data');
        $model = $data['id'] ?
            DeveloperModel::merge($data['id'], $data) :
            DeveloperModel::create($data);

        $vr = $model->validate();
        if ($vr !== true) {
            $this->session->set('errors', $lang->_('err_input_invalid'));
            return $this->getEditTemplateResult($model);
        }

        if (!$model->save()) {
            $this->session->set('errors', $lang->_('err_system'));
            return $this->getEditTemplateResult($model);
        }

        return Web\Result::RedirectResult($this->router->findHistory('list'));
    }

    public function deleteAction($id)
    {
        $model = DeveloperModel::load($id);

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
                'model' => $model
            ),
            'realty/developer/edit'
        );
    }
}