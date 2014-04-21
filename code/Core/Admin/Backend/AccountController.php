<?php
namespace Core\Admin\Backend;

use Core\Admin\Model\AdministratorModel;
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
        list($r, $ol) = \Ecleague\Tops::loadModel('admin/administrator')
            ->login($this->request->getPost('username'), $this->request->getPost('password'));

        if ($r === true) {
            $this->session->set('administrator', serialize(array(
                    'id' => $ol->getId(),
                    'username' => $ol->getUsername()
            )));
            return Web\Result::RedirectResult($this->router->buildUrl('admin/main/dashboard'));
        } else {
            switch ($r) {
                case AdministratorModel::ERROR_NOT_FOUND:
                case AdministratorModel::ERROR_PASSWORD:
                    $this->session->set('errors', $locale->_('admin_err_password'));
                    break;
                case AdministratorModel::ERROR_DISABLED:
                    $this->session->set('errors', $locale->_('admin_err_account_disabled'));
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