<?php
namespace Components\Content\Backend;

use Components\Content\Models\ArticleModel;
use Components\Content\Models\CategoryModel;
use Components\Content\Models\PublisherModel;
use Components\User\Models\AccountModel;
use Toy\Web;

class ArticleController extends Web\Controller
{

    public function listAction()
    {
        $pi = $this->request->getParameter("pageindex", 1);
        $count = ArticleModel::find()->fetchCount();
        $models = ArticleModel::find()
            ->select(PublisherModel::propertyToField('name', 'publisher'))
            ->select(CategoryModel::propertyToField('name', 'category'))
            ->join(PublisherModel::propertyToField('id'), ArticleModel::propertyToField('publisher_id'))
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