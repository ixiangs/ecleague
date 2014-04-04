<?php
namespace Core\Auth\Backend;

use Toy\Web;
use Auth\Model\BehaviorModel;

class BehaviorController extends Web\Controller
{

    public function listAction()
    {
        $pi = $this->request->getParameter("pageindex", 1);
        $count = BehaviorModel::find()->selectCount()->execute()->getFirstValue();
        $models = BehaviorModel::find()->limit(PAGINATION_SIZE, ($pi - 1) * PAGINATION_SIZE)->load();
        return Web\Result::templateResult(array(
                'models' => $models,
                'total' => $count,
                'pageIndex' => $pi)
        );
    }

    public function addAction()
    {
        return $this->getEditTemplateResult(BehaviorModel::create());
    }

    public function addPostAction()
    {
        $lang = $this->context->locale;
        $m = BehaviorModel::create($this->request->getAllParameters());
        $vr = $m->validateProperties();
        if ($vr !== true) {
            $this->session->set('errors', $lang->_('err_input_invalid'));
            return $this->getEditTemplateResult($m);
        }

        $vr = $m->validateUnique();
        if ($vr !== true) {
            $this->session->set('errors', $lang->_('err_code_exists', $m->getCode()));
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
        return $this->getEditTemplateResult(BehaviorModel::load($id));
    }

    public function editPostAction()
    {
        $lang = $this->context->locale;
        $m = BehaviorModel::merge($this->request->getParameter('id'), $this->request->getAllParameters());
        $vr = $m->validateProperties();
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
            'auth/behavior/edit'
        );
    }
}