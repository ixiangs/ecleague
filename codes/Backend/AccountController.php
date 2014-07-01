<?php
namespace Backend;

use Toy\Web;

class AccountController extends Web\Controller
{

    public function loginAction()
    {
        return Web\Result::templateResult();
    }

    public function loginPostAction()
    {
        $locale = $this->context->locale;
        list($r, $identity) = AccountModel::login($this->request->getPost('username'), $this->request->getPost('password'));

        if ($r === true) {
            $this->session->set('identity', $identity->getAllData());
            return Web\Result::RedirectResult(
                        $this->router->buildUrl($this->router->domain->getIndexUrl()));
        } else {
            switch ($r) {
                case Constant::ERROR_ACCOUNT_NOT_FOUND:
                case Constant::ERROR_ACCOUNT_PASSWORD:
                    $this->session->set('errors', $locale->_('user_err_password'));
                    break;
                case Constant::ERROR_ACCOUNT_DISABLED:
                case Constant::ERROR_ACCOUNT_NONACTIVATED:
                    $this->session->set('errors', $locale->_('user_err_account_disabled'));
                    break;
            }
            return Web\Result::templateResult();
        }
    }

    public function logoutAction()
    {
        $this->session->abort();
        header("Location: http://" . $_SERVER['HTTP_HOST'] . $this->router->buildUrl('index/index/index'));
        exit();
    }
}