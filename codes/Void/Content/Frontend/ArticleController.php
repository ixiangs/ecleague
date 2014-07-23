<?php
namespace Void\Content\Frontend;

use Toy\Util\RandomUtil;
use Void\Content\Constant;
use Void\Content\ArticleModel;
use Void\Content\CategoryModel;
use Toy\Platform\FileUtil;
use Toy\Platform\PathUtil;
use Toy\Web;

class ArticleController extends Web\Controller
{

    public function listAction()
    {
        $pi = $this->request->getQuery("pageindex", 1);
        $categoryid = $this->request->getQuery('categoryid');
        $find = ArticleModel::find();
        if (strlen($categoryid) > 0) {
            $find->eq(ArticleModel::propertyToField('category_id'), $categoryid);
        }
        $count = $find->fetchCount();

        $models = $find->resetSelect()
            ->select(ArticleModel::propertiesToFields())
            ->select(CategoryModel::propertyToField('name', 'category_name'))
            ->join(CategoryModel::propertyToField('id'), ArticleModel::propertyToField('category_id'), 'left')
            ->limit(PAGINATION_SIZE, ($pi - 1) * PAGINATION_SIZE)
            ->load();
        return Web\Result::templateResult(array(
                'models' => $models,
                'total' => $count)
        );
    }

    public function listAjaxAction()
    {
        $pi = $this->request->getQuery("pageindex", 1);
        $result = $this->listAction();
        $json = $result->data['models']->toArray(function ($item) {
            return array(null, array(
                'id' => $item->getId(),
                'title' => $item->getTitle(),
                'category' => $item->getCategoryName(),
                'introduction' => $item->getIntroduction(),
                'introImage' => $item->getIntroImage(),
                'link' => $this->router->buildUrl('detail',
                        array('websiteid' => $this->context->website->getId(), 'id' => $item->getId()))
            ));
        });
        return Web\Result::jsonResult(array(
            'over' => count($json) < PAGINATION_SIZE,
            'data' => $json
        ));
    }


    public function detailAction($id)
    {
        return Web\Result::templateResult(array('model' => ArticleModel::load($id)));
    }

}