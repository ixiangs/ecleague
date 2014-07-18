<?php
namespace Void\Content\Member;

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
        $title = $this->request->getQuery('title');
        $categoryid = $this->request->getQuery('categoryid');
        $find = ArticleModel::find()
            ->eq(ArticleModel::propertyToField('account_id'), $this->context->identity->getId());
        if($title){
            $find->like(ArticleModel::propertyToField('title'), $title);
        }
        if(strlen($categoryid) > 0){
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

    public function addAction()
    {
        $model = ArticleModel::create(array(
            'account_id' => $this->context->identity->getId()
        ));
        $model->createDirectory();
        return $this->getEditTemplateResult($model);
    }

    public function addPostAction()
    {
        return $this->save();
    }

    public function editAction($id)
    {
        return $this->getEditTemplateResult(ArticleModel::load($id));
    }

    public function editPostAction()
    {
        return $this->save();
    }

    public function uploadAction()
    {
        $upload = $this->request->getFile('imgFile');
        $dir = $this->request->getQuery('directory');
        $fname = 'source.' . $upload->getExtension();
        $path = PathUtil::combines(ASSET_PATH, 'articles', $dir);
        if ($upload->isOk() && $upload->isImage()) {
            $tmp = FileUtil::createSubDirectory($path);
            $target = PathUtil::combines($path, $tmp, $fname);
            FileUtil::moveUploadFile($upload->getTmpName(), $target);
            return Web\Result::jsonResult(array(
                'error' => 0,
                'url' => '/assets/articles/' . $dir . '/' . $tmp . '/' . $fname));
        }
        return Web\Result::jsonResult(array(
            'error' => 1,
            'message' => $this->localize->_('err_upload_article')));
    }

    private function save()
    {
        $identity = $this->context->identity;
        $data = $this->request->getPost('data');
        $model = $data['id'] ?
            ArticleModel::merge($data['id'], $data) :
            ArticleModel::create($data);
        $model->setPublisherId($this->session->get('publisherId'))
            ->setAccountId($identity->getId())
            ->setStatus(Constant::STATUS_ARTICLE_PUBLISHED);
        if ($model->validate() !== true) {
            $this->session->set('errors', $this->localize->_('err_input_invalid'));
            return $this->getEditTemplateResult($model);
        }

        if (!$model->save()) {
            $this->session->set('errors', $this->localize->_('err_system'));
            return $this->getEditTemplateResult($model);;
        }

        return Web\Result::redirectResult($this->router->findHistory('list'));
    }

    public function deleteAction($id)
    {
        $lang = $this->context->localize;
        $m = ArticleModel::load($id);

        if (!$m) {
            $this->session->set('errors', $lang->_('err_system'));
            return Web\Result::redirectResultt($this->router->buildUrl('list'));
        }

        if (!$m->delete()) {
            $this->session->set('errors', $lang->_('err_system'));
            return Web\Result::redirectResult($this->router->buildUrl('list'));
        }
        return Web\Result::redirectResult($this->router->buildUrl('list'));
    }

    public function selectAction(){
        $result = $this->listAction();
        $categories = CategoryModel::find()
            ->eq('account_id', $this->context->identity->getId())
            ->fetch()
            ->combineColumns('id', 'name');
        $categories[0] = $this->localize->_('content_uncategory');
        ksort($categories);
        $data = $result->data;
        $data['categories'] = $categories;
        $result->data = $data;
        return $result;
    }

    private function getEditTemplateResult($model)
    {
        $categories = CategoryModel::find()
            ->eq(CategoryModel::propertyToField('account_id'), $this->context->identity->getId())
            ->fetch()
            ->combineColumns('id', 'name');
        $categories[0] = $this->localize->_('content_uncategory');
        return Web\Result::templateResult(
            array('model' => $model, 'categories' => $categories),
            'edit'
        );
    }
}