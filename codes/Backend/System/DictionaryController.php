<?php
namespace Ixiangs\System;

use Toy\Db\Helper;
use Toy\View\Html\Document;
use Toy\Web;

class DictionaryController extends Web\Controller
{

    public function listAction()
    {
        $lid = $this->request->getParameter('languageid');
        $pi = $this->request->getParameter("pageindex", 1);
        $lang = LanguageModel::load($lid);
        $find = DictionaryModel::find()->eq('language_id', $lid);
        list($code, $label) = $this->request->listQuery('code', 'label');
        if ($code) {
            $find->like('code', '%' . $code . '%');
        }
        if ($label) {
            $find->like('label', '%' . $label . '%');
        }
        $models = $find
            ->asc('code')
            ->limit(PAGINATION_SIZE, ($pi - 1) * PAGINATION_SIZE)
            ->load();
        $count = $find->executeCount();
        Document::singleton()->addBreadcrumbs($lang->getName(), $this->router->findHistory('language/list'));
        return Web\Result::templateResult(array(
                'models' => $models,
                'language' => $lang,
                'total' => $count,
                'pageIndex' => $pi)
        );
    }

    public function addAction($languageid)
    {
        return $this->getAddTemplateResult(array(new DictionaryModel()));
    }

    public function addPostAction()
    {
        $lang = $this->context->locale;
        list($codes, $labels, $languageId) = $this->request->listPost('codes', 'labels', 'language_id');
        $models = array();

        foreach ($codes as $index => $code) {
            $models[] = new DictionaryModel(array(
                'code' => $code,
                'label' => $labels[$index],
                'language_id' => $languageId
            ));
        }

        foreach ($models as $m) {
            $vr = $m->validate();
            if ($vr !== true) {
                $this->session->set('errors', $lang->_('err_input_invalid'));
                return $this->getAddTemplateResult($models);
            }
        }

        $success = Helper::withTx(function ($tx) use ($models) {
            foreach ($models as $m) {
                if (!$m->save($tx)) {
                    $tx->rollback();
                    return false;
                }
            }
            return true;
        });

        if ($success) {
            return Web\Result::redirectResult($this->router->findHistory('list', array('languageid' => $languageId)));
        } else {
            $this->session->set('errors', $lang->_('err_system'));
            return $this->getAddTemplateResult($models);
        }

    }

    public function editAction($id)
    {
        return $this->getEditTemplateResult(DictionaryModel::load($id));
    }

    public function editPostAction($languageid)
    {
        $lang = $this->context->locale;
        $data = $this->request->getPost('data');
        $model = DictionaryModel::merge($data['id'], $data);
        $vr = $model->validate();
        if ($vr !== true) {
            $this->session->set('errors', $lang->_('err_input_invalid'));
            return $this->getEditTemplateResult($model);
        }

        if (!$model->save()) {
            $this->session->set('errors', $lang->_('err_system'));
            return $this->getEditTemplateResult($model);
        }

        return Web\Result::redirectResult($this->router->findHistory('list', array('languageid' => $languageid)));
    }

    public function deletePostAction($languageid)
    {
        $lang = $this->context->locale;
        $result = DictionaryModel::batchDelete($this->request->getPost('ids'));

        if (!$result) {
            $this->session->set('errors', $lang->_('err_system'));
        }

        return Web\Result::redirectResult($this->request->getRefererUrl());
    }

    private function getAddTemplateResult($models)
    {
        $lid = $this->request->getParameter('languageid');
        return Web\Result::templateResult(array(
            'models' => $models,
            'language' => LanguageModel::load($lid)));
    }

    private function getEditTemplateResult($model)
    {
        $languageId = $this->request->getQuery('languageid');
        return Web\Result::templateResult(
            array('model' => $model, 'language' => LanguageModel::load($languageId)),
            'ixiangs/system/dictionary/edit'
        );
    }
}