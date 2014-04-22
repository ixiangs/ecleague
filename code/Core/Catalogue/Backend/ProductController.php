<?php
namespace Core\Catalogue\Backend;

use Toy\Data\Helper;
use Toy\Web;

class ProductController extends Web\Controller
{

    public function listAction()
    {
        $pi = $this->request->getParameter("pageindex", 1);
        $count = \Ecleague\Tops::loadModel('catalogue/product')->find()->selectCount()->execute()->getFirstValue();
        $models = \Ecleague\Tops::loadModel('catalogue/product')->find()
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
        return $this->getEditTemplateResult(\Ecleague\Tops::loadModel('catalogue/product'));
    }

    public function addPostAction()
    {
        $locale = $this->context->locale;
        $member = \Ecleague\Tops::loadModel('catalogue/product')->fillArray($this->request->getPost('member'));
        $account = \Ecleague\Tops::loadModel('auth/account')
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
        $m = \Ecleague\Tops::loadModel('catalogue/product');
        $m->load($id);
        return $this->getEditTemplateResult($m, null);
    }

    public function editPostAction()
    {
        $locale = $this->context->locale;
        $m = \Ecleague\Tops::loadModel('catalogue/product')->fillArray($this->request->getPost('data'));

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
        $m = \Ecleague\Tops::loadModel('catalogue/product')->load($id);

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
        $attrTree = \Core\Attrs\Helper::getAttributeTree('clothing');
        return Web\Result::templateResult(
            array('model' => $model, 'attributeSet'=>$attrTree),
            'catalogue/product/edit'
        );
    }
}