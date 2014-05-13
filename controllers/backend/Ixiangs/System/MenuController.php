<?php
namespace Ixiangs\System;

use Toy\Db\Helper;
use Toy\Web;

class MenuController extends Web\Controller
{

    public function listAction()
    {
        $pi = $this->request->getParameter("pageindex", 1);
        $count = MenuModel::find()->count();
        $models = MenuModel::find()
            ->limit(PAGINATION_SIZE, ($pi - 1) * PAGINATION_SIZE)
            ->load();
        return Web\Result::templateResult(array(
                'models' => $models,
                'total' => $count,
                'pageIndex' => $pi)
        );
    }

    public function sortAction()
    {
        $menus = MenuModel::find()
            ->asc('parent_id', 'position')
            ->load();
        return Web\Result::templateResult(array('menus' => $menus));
    }

    public function sortPostAction()
    {
        $data = $this->request->getPost('data');
        Helper::withTx(function($db) use($data){
            MenuModel::sort($data, $db);
        });

        return $this->sortAction();
    }

    public function addAction()
    {
        return $this->getEditTemplateResult(new MenuModel());
    }

    public function savePostAction()
    {
        $locale = $this->context->locale;
        $m = new MenuModel($this->request->getPost('data'));

        $vr = $m->validateProperties();
        if ($vr !== true) {
            $this->session->set('errors', $locale->_('err_input_invalid'));
            return $this->getEditTemplateResult($m);
        }

        if ($m->getId()) {
            if (!$m->update()) {
                $this->session->set('errors', $locale->_('err_system'));
                return $this->getEditTemplateResult($m);
            }
        } else {
            if (!$m->insert()) {
                $this->session->set('errors', $locale->_('err_system'));
                return $this->getEditTemplateResult($m);
            }
        }

        return Web\Result::redirectResult($this->router->buildUrl('list'));
    }

    public function editAction($id)
    {
        return $this->getEditTemplateResult(MenuModel::load($id));
    }

    public function deleteAction($id)
    {
        $lang = $this->context->locale;
        $m = MenuModel::load($id);

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
        $locale = $this->context->locale;
        $lid = $locale->getLanguageId();
        $find = MenuModel::find()->asc('parent_id');
        if ($model->getId()) {
            $find->ne('id', $model->getId());
        }

        $menus = $find->load()->toArray(function ($item) use ($lid) {
            return array(null, array(
                'id' => $item->getId(),
                'parentId' => $item->getParentId(),
                'value' => $item->getId(),
                'text' => $item->name[$lid],
            ));
        });
        return Web\Result::templateResult(
            array('model' => $model, 'menus' => $menus),
            'ixiangs/system/menu/edit'
        );
    }
}