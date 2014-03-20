<?php
namespace Core\Locale\Backend;

use Toy\Web;
use Locale\Model\LanguageModel;

class LanguageController extends Web\Controller
{

    public function listAction()
    {
        $pi = $this->request->getParameter("pageindex", 1);
        $count = LanguageModel::find()->selectCount()->execute()->getFirstValue();
        $models = LanguageModel::find()->limit(PAGINATION_SIZE, ($pi - 1) * PAGINATION_SIZE)->execute()->getModelArray();
        return Web\Result::templateResult(array(
                'models' => $models,
                'total' => $count,
                'pageIndex' => $pi)
        );
    }

    public function addAction()
    {
        return $this->getEditTemplateResult(LanguageModel::create());
    }

    public function addPostAction()
    {
        $lang = $this->context->locale;
        $m = LanguageModel::create($this->request->getAllParameters());
        if (LanguageModel::checkUnique('code', $m->getCode())) {
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
        return $this->getEditTemplateResult(LanguageModel::load($id));
    }

    public function editPostAction()
    {
        $lang = $this->context->locale;
        $m = LanguageModel::merge($this->request->getParameter('id'), $this->request->getAllParameters());
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
        return Web\Result::templateResult(
            array('model' => $model),
            'locale/language/edit'
        );
    }
}