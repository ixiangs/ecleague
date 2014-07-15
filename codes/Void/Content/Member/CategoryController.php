<?php
namespace Void\Content\Member;

use Void\Content\CategoryModel;
use Void\Auth\RoleModel;
use Toy\Web;

class CategoryController extends Web\Controller
{

    public function listAction()
    {
        $pi = $this->request->getParameter("pageindex", 1);
        $count = CategoryModel::find()
                    ->eq('account_id', $this->context->identity->getId())
                    ->fetchCount();
        $models = CategoryModel::find()
            ->eq('account_id', $this->context->identity->getId())
            ->limit(PAGINATION_SIZE, ($pi - 1) * PAGINATION_SIZE)
            ->load();
        return Web\Result::templateResult(array(
                'models' => $models,
                'total' => $count)
        );
    }

    public function addAction()
    {
        return $this->getEditTemplateReult(new CategoryModel());
    }

    public function addPostAction()
    {
        return $this->save();
    }

    public function editAction($id)
    {
        return $this->getEditTemplateReult(CategoryModel::load($id));
    }

    public function editPostAction($id)
    {
        return $this->save();
    }

    public function save()
    {
        $data = $this->request->getPost('data');
        $model = $data['id'] ?
            CategoryModel::merge($data['id'], $data) :
            CategoryModel::create($data);
        $model->setParentId(0)
            ->setAccountId($this->context->identity->getId());

        $vr = $model->validate();
        if ($vr !== true) {
            $this->session->set('errors', $this->localize->_('err_input_invalid'));
            return $this->getEditTemplateReult($model);
        }

        if (!$model->save()) {
            $this->session->set('errors', $this->localize->_('err_system'));
            return $this->getEditTemplateReult($model);
        }

        return Web\Result::redirectResult($this->router->findHistory('list'));
    }

    public function deleteAction($id)
    {
        $m = CategoryModel::load($id);

        if (!$m) {
            $this->session->set('errors', $this->localize->get('err_system'));
            return Web\Result::redirectResult($this->router->buildUrl('index'));
        }

        if (!$m->delete()) {
            $this->session->set('errors', $this->localize->get('err_system'));
            return Web\Result::redirectResultt($this->router->buildUrl('index'));
        }
        return Web\Result::redirectResult($this->router->buildUrl('list'));
    }

    private function getEditTemplateReult($model)
    {
        return Web\Result::templateResult(
            array('model' => $model),
            'edit'
        );
    }
}