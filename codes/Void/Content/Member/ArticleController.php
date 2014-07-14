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
        $pi = $this->request->getParameter("pageindex", 1);
        $count = ArticleModel::find()->fetchCount();
        $models = ArticleModel::find()
            ->select(CategoryModel::propertyToField('name', 'category_name'))
            ->join(CategoryModel::propertyToField('id'), ArticleModel::propertyToField('category_id'), 'left')
            ->eq(ArticleModel::propertyToField('account_id'), $this->context->identity->getId())
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
        $path = PathUtil::combines(ASSET_PATH, 'articles', $this->context->identity->getId(), $dir);
        if ($upload->isOk() && $upload->isImage()) {
            $tmp = FileUtil::createSubDirectory($path);
            $target = PathUtil::combines($path, $tmp, $fname);
            FileUtil::moveUploadFile($upload->getTmpName(), $target);
            return Web\Result::jsonResult(array(
                'error' => 0,
                'url' => '/assets/articles/' . $this->context->identity->getId() . '/' . $dir . '/' . $tmp . '/' . $fname));
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
            ->setEditorId($identity->getId())
            ->setStatus(Constant::STATUS_ARTICLE_PUBLISHED);
        if ($model->validate() !== true) {
            $this->session->set('errors', $this->localize->_('err_input_invalid'));
            return $this->getEditTemplateReult($model);
        }

        if (!$model->save()) {
            $this->session->set('errors', $this->localize->_('err_system'));
            return $this->getEditTemplateReult($model);;
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

    private function getEditTemplateResult($model)
    {
        $categories = CategoryModel::find()
            ->eq(CategoryModel::propertyToField('account_id'), $this->context->identity->getId())
            ->fetch()
            ->combineColumns('id', 'name');
        $categories[0] = $this->localize->_('content_uncategory');
        return Web\Result::templateResult(
            array('model' => $model, 'categories' => $categories),
            'void/content/article/edit'
        );
    }
}