<?php
namespace Ixiangs\System;

use Toy\Platform\FileUtil;
use Toy\Web;

class LanguageController extends Web\Controller
{

    public function listAction()
    {
        $pi = $this->request->getParameter("pageindex", 1);
        $count = LanguageModel::find()->executeCount();
        $models = LanguageModel::find()->limit(PAGINATION_SIZE, ($pi - 1) * PAGINATION_SIZE)->load();
        return Web\Result::templateResult(array(
                'models' => $models,
                'total' => $count,
                'pageIndex' => $pi)
        );
    }

    public function addAction()
    {
        return $this->getEditTemplateResult(new LanguageModel());
    }

    public function editAction($id)
    {
        return $this->getEditTemplateResult(LanguageModel::load($id));
    }

    public function savePostAction()
    {
        $lang = $this->context->locale;
        $m = new LanguageModel($this->request->getPost('data'));

        $vr = $m->validate();
        if ($vr !== true) {
            $this->session->set('errors', $lang->_('err_input_invalid'));
            return $this->getEditTemplateResult($m);
        }

        if ($m->getId()) {
            if (!$m->update()) {
                $this->session->set('errors', $lang->_('err_system'));
                return $this->getEditTemplateResult($m);
            }
        } else {
            $vr = $m->checkUnique();
            if ($vr !== true) {
                $this->session->set('errors', $lang->_('locale_err_language_exists', $m->getCode()));
                return $this->getEditTemplateResult($m);
            }
            if (!$m->insert()) {
                $this->session->set('errors', $lang->_('err_system'));
                return $this->getEditTemplateResult($m);
            }
        }

        return Web\Result::redirectResult($this->router->getHistoryUrl('list'));
    }

    public function deleteAction($id)
    {
        $lang = $this->context->locale;
        $model = LanguageModel::load($id);

        if ($model) {
            if (!$model->delete()) {
                $this->session->set('errors', $lang->_('err_system'));
            }
        }else{
            $this->session->set('errors', $lang->_('err_system'));
        }

        return Web\Result::redirectResult($this->router->getHistoryUrl('list'));
    }

    public function importAction()
    {
        return Web\Result::templateResult();
    }

    public function importPostAction()
    {
        $lang = $this->context->locale;
        $up = $this->request->getFile('upload');
        if (!$up->checkExtension('csv')) {
            $this->session->set('errors', $lang->_('locale_err_import'));
            return Web\Result::templateResult();
        }
        $langs = $this->context->locale->getAllLanguages();
        $lines = FileUtil::readCsv($up->getTmpName());
        $titles = array_shift($lines);
        foreach ($lines as $line) {
            for ($i = 1; $i < count($titles); $i++) {
                DictionaryModel::create(array(
                    'code' => $line[0],
                    'label' => $line[$i],
                    'language_id' => $langs[strtolower($titles[$i])]['id']
                ))->insert();
            }
        }

        return Web\Result::redirectResult($this->router->buildUrl('list'));
    }

    private function getEditTemplateResult($model)
    {
        return Web\Result::templateResult(
            array('model' => $model),
            'ixiangs/system/language/edit'
        );
    }
}