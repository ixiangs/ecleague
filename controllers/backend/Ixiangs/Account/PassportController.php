<?php
namespace Ixiangs\Account;

use Ixiangs\User;
use Toy\Web;

class PassportController extends Web\Controller
{

    public function loginAction()
    {
        return Web\Result::templateResult();
    }

    public function loginPostAction()
    {
        $locale = $this->context->locale;
        list($r, $identity) = User\AccountModel::login($this->request->getPost('username'), $this->request->getPost('password'));

        if ($r === true) {
            $this->session->set('identity', $identity);
            return Web\Result::RedirectResult($this->router->buildUrl('dashboard/index'));
        } else {
            switch ($r) {
                case User\Constant::ERROR_ACCOUNT_NOT_FOUND:
                case User\Constant::ERROR_ACCOUNT_PASSWORD:
                    $this->session->set('errors', $locale->_('user_err_password'));
                    break;
                case User\Constant::ERROR_ACCOUNT_DISABLED:
                case User\Constant::ERROR_ACCOUNT_NONACTIVATED:
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