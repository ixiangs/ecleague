<?php
namespace Components\Content\Member;

use Components\Content\Models\CategoryModel;
use Components\Auth\Models\RoleModel;
use Toy\Web;

class CategoryController extends Web\Controller
{

    public function listAction()
    {
        $pi = $this->request->getParameter("pageindex", 1);
        $count = CategoryModel::find()
                    ->eq(CategoryModel::propertyToField('publisher_id'),
                            $this->context->identity->getItem('publisher_id'))
                    ->fetchCount();
        $models = CategoryModel::find()
            ->eq(CategoryModel::propertyToField('publisher_id'),
                $this->context->identity->getItem('publisher_id'))
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
        $lang = $this->context->localize;
        $data = $this->request->getPost('data');
        $model = $data['id'] ?
            CategoryModel::merge($data['id'], $data) :
            CategoryModel::create($data);
        $model->setPublisherId($this->context->identity->getItem('publisher_id'))
            ->setParentId(0)
            ->setAccountId($this->context->identity->getId());

        $vr = $model->validate();
        if ($vr !== true) {
            $this->session->set('errors', $lang->_('err_input_invalid'));
            return $this->getEditTemplateReult($model);
        }

        if (!$model->save()) {
            $this->session->set('errors', $lang->_('err_system'));
            return $this->getEditTemplateReult($model);
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
            array('model' => $model),
            'content/category/edit'
        );
    }
}