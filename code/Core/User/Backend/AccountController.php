<?php
namespace Core\User\Backend;

use Ecleague\Tops;
use Toy\Web;

class AccountController extends Web\Controller
{

    public function listAction()
    {
        $pi = $this->request->getParameter("pageindex", 1);
        $count = Tops::loadModel('user/account')->find()->selectCount()->execute()->getFirstValue();
        $models = Tops::loadModel('user/account')->find()
            ->limit(PAGINATION_SIZE, ($pi - 1) * PAGINATION_SIZE)
            ->load();
        return Web\Result::templateResult(array(
                'models' => $models,
                'total' => $count,
                'pageIndex' => $pi)
        );
    }

    public function addAction()
    {
        return $this->getEditTemplateResult(Tops::loadModel('user/account'));
    }

    public function addPostAction()
    {
        $locale = $this->context->locale;
        $user = Tops::loadModel('user/account')->fillArray($this->request->getPost('user'));

        $vr = $user->validateProperties();
        if ($vr !== true) {
            $this->session->set('errors', $locale->_('err_input_invalid'));
            return $this->getEditTemplateResult($user);
        }

        $vr = $user->validateUnique();
        if ($vr !== true) {
            $this->session->set('errors', $locale->_('user_err_account_exists', $user->getUsername()));
            return $this->getEditTemplateResult($user);
        }


        if (!$user->insert()) {
            $this->session->set('errors', $locale->_('err_system'));
            return $this->getEditTemplateResult($user);;
        }

        return Web\Result::redirectResult($this->router->buildUrl('list'));
    }

    public function editAction($id)
    {
        $m = Tops::loadModel('user/account');
        $m->load($id);
        return $this->getEditTemplateResult($m);
    }

    public function editPostAction()
    {
        $locale = $this->context->locale;
        $m = Tops::loadModel('user/account')->fillArray($this->request->getPost('data'));

        $vr = $m->validateProperties();
        if ($vr !== true) {
            $this->session->set('errors', $locale->_('err_input_invalid'));
            return $this->getEditTemplateResult($m);
        }

        if (!$m->update()) {
            $this->session->set('errors', $locale->_('err_system'));
            return $this->getEditTemplateResult($m);
        }

        return Web\Result::redirectResult($this->router->buildUrl('list'));
    }

    public function deleteAction($id)
    {
        $lang = $this->context->locale;
        $m = Tops::loadModel('user/account')->load($id);

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

    private function getEditTemplateResult($user)
    {
        return Web\Result::templateResult(
            array('model' => $user),
            'user/account/edit'
        );
    }
}