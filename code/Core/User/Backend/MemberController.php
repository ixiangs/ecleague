<?php
namespace Core\User\Backend;

use Core\Auth\Model\AccountModel;
use Toy\Data\Helper;
use Toy\Util\ArrayUtil;
use Toy\Web;

class MemberController extends Web\Controller
{

    public function listAction()
    {
        $pi = $this->request->getParameter("pageindex", 1);
        $count = \Tops::loadModel('user/member')->find()->selectCount()->execute()->getFirstValue();
        $models = \Tops::loadModel('user/member')->find()
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
        return $this->getEditTemplateResult(\Tops::loadModel('user/member'), \Tops::loadModel('auth/account'));
    }

    public function addPostAction()
    {
        $locale = $this->context->locale;
        $member = \Tops::loadModel('user/member')->fillArray($this->request->getPost('member'));
        $account = \Tops::loadModel('auth/account')
                    ->fillArray($this->request->getPost('account'))
                    ->setStatus(AccountModel::STATUS_ACTIVATED)
                    ->setLevel(AccountModel::LEVEL_NORMAL);

        $vr = $account->validateProperties();
        if ($vr !== true) {
            $this->session->set('errors', $locale->_('err_input_invalid'));
            return $this->getEditTemplateResult($member, $account);
        }

        $vr = $account->validateUnique();
        if ($vr !== true) {
            $this->session->set('errors', $locale->_('auth_err_account_exists', $account->getCode()));
            return $this->getEditTemplateResult($member, $account);
        }

        $res = Helper::withTx(function ($db) use ($member, $account, $locale) {
            if (!$account->insert($db)) {
                $this->session->set('errors', $locale->_('err_system'));
                return false;
            }

            $member->setAccountId($account->getId());
            $vr = $member->validateProperties();

            if ($vr !== true) {
                $this->session->set('errors', $locale->_('err_input_invalid'));
                return false;
            }

            if (!$member->insert($db)) {
                $this->session->set('errors', $locale->_('err_system'));
                return false;
            }

            return true;
        });

        return $res === true ?
            Web\Result::redirectResult($this->router->buildUrl('list')) :
            $this->getEditTemplateResult($member, $account);
    }

    public function editAction($id)
    {
        $m = \Tops::loadModel('user/member');
        $m->load($id);
        return $this->getEditTemplateResult($m, null);
    }

    public function editPostAction()
    {
        $locale = $this->context->locale;
        $m = \Tops::loadModel('user/member')->fillArray($this->request->getPost('data'));

        $vr = $m->validateProperties();
        if ($vr !== true) {
            $this->session->set('errors', $locale->_('err_input_invalid'));
            return $this->getEditTemplateResult($m, null);
        }

        if (!$m->update()) {
            $this->session->set('errors', $locale->_('err_system'));
            return $this->getEditTemplateResult($m, null);
        }

        return Web\Result::redirectResult($this->router->buildUrl('list'));
    }

    public function deleteAction($id)
    {
        $lang = $this->context->locale;
        $m = \Tops::loadModel('user/member')->load($id);

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

    private function getEditTemplateResult($member, $account)
    {
        return Web\Result::templateResult(
            array('member' => $member, 'account' => $account),
            'user/member/edit'
        );
    }
}