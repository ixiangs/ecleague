<?php
namespace Ixiangs\System;

use Toy\Db\Helper;
use Toy\Web;

class DictionaryController extends Web\Controller
{

    public function listAction()
    {
        $lid = $this->request->getParameter('languageid');
        $pi = $this->request->getParameter("pageindex", 1);
        $lang = LanguageModel::load($lid);
        $find = DictionaryModel::find()->eq('language_id', $lid);
        if ($this->request->getParameter('kwcode')) {
            $find->like('code', '%' . $this->request->getParameter('kwcode') . '%');
        }
        if ($this->request->getParameter('kwlabel')) {
            $find->like('label', '%' . $this->request->getParameter('kwlabel') . '%');
        }
        $models = $find
            ->asc('code')
            ->limit(PAGINATION_SIZE, ($pi - 1) * PAGINATION_SIZE)
            ->load();
        $count = $find->resetLimit()->count();
        return Web\Result::templateResult(array(
                'models' => $models,
                'language' => $lang,
                'total' => $count,
                'pageIndex' => $pi)
        );
    }

    public function addAction()
    {
        $lid = $this->request->getParameter('languageid');
        return $this->getAddTemplateResult(array(new DictionaryModel()));
    }

    public function addPostAction()
    {
        $lang = $this->context->locale;
        $codes = $this->request->getParameter('codes');
        $labels = $this->request->getParameter('labels');
        $lid = $this->request->getParameter('languageid');
        $models = array();

        foreach ($codes as $index => $code) {
            $models[] = new DictionaryModel(array(
                'code' => $code,
                'label' => $labels[$index],
                'language_id' => $lid
            ));
        }

        foreach ($models as $m) {
            $vr = $m->validateProperties();
            if ($vr !== true) {
                $this->session->set('errors', $lang->_('err_input_invalid'));
                return $this->getAddTemplateResult($models);
            }
        }

        try {
            Helper::withTx(function ($db) use ($models) {
                foreach ($models as $m) {
                    if (!$m->insert()) {
                        throw new \Exception();
                    }
                }
            });
        } catch (\Exception $ex) {
            $this->session->set('errors', $lang->_('err_system'));
            return $this->getAddTemplateResult($models);
        }

        return Web\Result::redirectResult($this->router->buildUrl('list', array('languageid' => $lid)));
    }

    public function editAction($id)
    {
        return $this->getEditTemplateResult(DictionaryModel::load($id));
    }

    public function editPostAction($languageid)
    {
        $lang = $this->context->locale;
        $data = $this->request->getPost('data');
        $m = DictionaryModel::create($data);
        $vr = $m->validateProperties();
        if ($vr !== true) {
            $this->session->set('errors', $lang->_('err_input_invalid'));
            return $this->getEditTemplateResult($m);
        }

        if (!$m->update()) {
            $this->session->set('errors', $lang->_('err_system'));
            return $this->getEditTemplateResult($m);
        }

        return Web\Result::redirectResult($this->router->buildUrl('list', array('languageid' => $languageid)));
    }

    public function deletePostAction($languageid)
    {
//        $lang = $this->context->locale;
//        $m = LanguageModel::deleteBatch($this->request->getPost('ids'));
//
//        if (!$m) {
//            $this->session->set('errors', $lang->_('err_system'));
//            return Web\Result::redirectResultt($this->router->buildUrl('list'));
//        }
//
//        return Web\Result::redirectResult($this->router->buildUrl('list', array('languageid' => $languageid)), $lang->_('operation_success'));
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
        $lid = $this->request->getParameter('languageid');
        return Web\Result::templateResult(
            array('model' => $model,
                'language' => LanguageModel::load($lid)),
            'ixiangs/system/dictionary/edit'
        );
    }
}