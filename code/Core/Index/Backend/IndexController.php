<?php
namespace Core\Index\Backend;

use Core\Auth\Model\AccountModel;
use Toy\Web;

class IndexController extends Web\Controller
{

    public function indexAction()
    {
        return Web\Result::templateResult();
    }

    public function indexPostAction()
    {
        $langs = $this->context->locale;
        list($r, $ol) = \Tops::loadModel('auth/account')
            ->login($this->request->getPost('username'), $this->request->getPost('password'));

        if ($r === true) {
            if ($ol->getLevel() != AccountModel::LEVEL_ADMINISTRATOR) {
                $this->session->set('errors', $this->languages->get('permission_denied'));
                return new TemplateResult();
            }
            $this->session->set('identity', serialize(array('id' => $ol->getId(),
                    'username' => $ol->getUsername(),
                    'level' => $ol->getLevel(),
                    'roles' => $ol->getRoles(),
                    'behaviors' => $ol->getBehaviors())
            ));
            return Web\Result::RedirectResult($this->router->buildUrl('auth/account/list'));
        } else {
            switch ($r) {
                case AccountModel::ERROR_NOT_FOUND:
                case AccountModel::ERROR_PASSWORD:
                    $this->session->set('errors', $langs->_('auth_err_password'));
                    break;
                case AccountModel::ERROR_NONACTIVATED:
                    $this->session->set('errors', $langs->_('auth_err_account_nonactivated'));
                    break;
                case AccountModel::ERROR_DISABLED:
                    $this->session->set('errors', $langs->_('auth_err_account_disabled'));
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