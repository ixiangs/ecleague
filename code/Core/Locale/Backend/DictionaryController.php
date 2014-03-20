<?php
namespace Core\Locale\Backend;

use Toy\Web;
use Locale\Model\DictionaryModel;
use Locale\Model\LanguageModel;

class DictionaryController extends Web\Controller
{

    public function listAction()
    {
        $lid = $this->request->getParameter('languageid');
        $pi = $this->request->getParameter("pageindex", 1);
        $lang = LanguageModel::load($lid);
        $count = DictionaryModel::find()->selectCount()->execute()->getFirstValue();
        $models = DictionaryModel::find()
                        ->eq('language_id', $lid)
                        ->limit(PAGINATION_SIZE, ($pi - 1) * PAGINATION_SIZE)
                        ->execute()
                        ->getModelArray();
        return Web\Result::templateResult(array(
                'models' => $models,
                'language'=>$lang,
                'total' => $count,
                'pageIndex' => $pi)
        );
    }

    public function addAction()
    {
        return $this->getEditTemplateResult(DictionaryModel::create());
    }

    public function addPostAction()
    {
        $lang = $this->context->locale;
        $m = DictionaryModel::create($this->request->getAllParameters());
        if (DictionaryModel::checkUnique('code', $m->getCode())) {
            $this->session->set('errors', $lang->_('err_code_exists', $m->getCode()));
            return $this->getEditTemplateResult($m);
        }

        $vr = $m->validate();
        if ($vr !== true) {
            $this->session->set('errors', $lang->_('err_input_invalid'));
            return $this->getEditTemplateResult($m);
        }

        if (!$m->insert()) {
            $this->session->set('errors', $lang->_('err_system'));
            return $this->getEditTemplateResult($m);
        }

        return Web\Result::redirectResult($this->router->buildUrl('list'));
    }

    public function editAction($id)
    {
        return $this->getEditTemplateResult(DictionaryModel::load($id));
    }

    public function editPostAction()
    {
        $lang = $this->context->locale;
        $m = DictionaryModel::merge($this->request->getParameter('id'), $this->request->getAllParameters());
        $vr = $m->validate();
        if ($vr !== true) {
            $this->session->set('errors', $lang->_('err_input_invalid'));
            return $this->getEditTemplateResult($m);
        }

        if (!$m->update()) {
            $this->session->set('errors', $lang->_('err_system'));
            return $this->getEditTemplateResult($m);
        }

        return Web\Result::redirectResult($this->router->buildUrl('list'));
    }

    public function deleteAction($id)
    {
        $lang = $this->context->locale;
        $m = BehaviorModel::load($id);

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
        $lid = $this->request->getParameter('languageid');
        return Web\Result::templateResult(
            array('model' => $model,
                  'language'=>LanguageModel::load($lid)),
            'locale/dictionary/edit'
        );
    }
}