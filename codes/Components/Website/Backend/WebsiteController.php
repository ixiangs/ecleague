<?php
namespace Components\Website\Backend;

use Components\Auth\Models\AccountModel;
use Components\Website\Models\WebsiteModel;
use Toy\Web;

class WebsiteController extends Web\Controller
{

    public function listAction()
    {
        $pi = $this->request->getParameter("pageindex", 1);
        $count = WebsiteModel::find()->fetchCount();
        $models = WebsiteModel::find()
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
        return $this->getEditTemplateResult(WebsiteModel::create());
    }

    public function editAction($id)
    {
        return $this->getEditTemplateResult(WebsiteModel::load($id));
    }

    public function savePostAction()
    {
        $lang = $this->context->localize;
        $data = $this->request->getPost('data');
        $model = $data['id'] ?
            WebsiteModel::merge($data['id'], $data) :
            WebsiteModel::create($data);

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
        $model = WebsiteModel::load($id);

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
        $existsIds = WebsiteModel::find()
            ->select('account_id')
            ->fetch()
            ->getColumnValues('account_id');
        $accounts = AccountModel::find()->load()
            ->filter(function ($item) use ($existsIds) {
                if(in_array('backend', $item->getDomains())){
                    return false;
                }
                return !in_array($item->getId(), $existsIds);
            })->toArray(function ($item) {
                return array($item->getId(), $item->getUsername());
            });
        return Web\Result::TemplateResult(
            array(
                'model' => $model, 'accounts' => $accounts
            ),
            'website/website/edit'
        );
    }
}