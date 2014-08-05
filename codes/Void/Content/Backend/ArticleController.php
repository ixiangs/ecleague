<?php
namespace Void\Content\Backend;

use Void\Content\ArticleModel;
use Void\Content\CategoryModel;
use Toy\Web;

class ArticleController extends Web\Controller
{

    public function listAction()
    {
        $pi = $this->request->getParameter("pageindex", 1);
        $count = ArticleModel::find()->fetchCount();
        $models = ArticleModel::find()
            ->select(CategoryModel::propertyToField('name', 'category'))
            ->join(CategoryModel::propertyToField('id'), ArticleModel::propertyToField('category_id'))
            ->limit(PAGINATION_SIZE, ($pi - 1) * PAGINATION_SIZE)
            ->load();
        return Web\Result::templateResult(array(
                'models' => $models,
                'total' => $count)
        );
    }

    public function deleteAction($id)
    {
        $m = PublisherModel::load($id);

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
}