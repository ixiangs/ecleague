<?php
namespace Void\Realty\Backend;

use Void\Realty\DeveloperModel;
use Void\Realty\UptownModel;
use Void\Auth\AccountModel;
use Toy\Orm\Db\Helper;
use Toy\Web;

class UptownController extends Web\Controller
{

    public function listAction()
    {
        $pi = $this->request->getParameter("pageindex", 1);
        $count = UptownModel::find()->fetchCount();
        $models = UptownModel::find()
            ->select(DeveloperModel::propertyToField('name', 'developer_name'))
            ->select(UptownModel::propertiesToFields())
            ->join(DeveloperModel::propertyToField('id'), UptownModel::propertyToField('developer_id'))
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
        return $this->getEditTemplateResult(new UptownModel());
    }

    public function editAction($id)
    {
        return $this->getEditTemplateResult(UptownModel::load($id));
    }

    public function savePostAction()
    {
        $lang = $this->context->localize;
        $data = $this->request->getPost('data');
        $model = $data['id'] ?
            UptownModel::merge($data['id'], $data) :
            UptownModel::create($data);

        $vr = $model->validate();
        if ($vr !== true) {
            $this->session->set('errors', $lang->_('err_input_invalid'));
            return $this->getEditTemplateReult($model);
        }

        if ($model->isNewed()) {
            $vr = $model->checkUnique();
            if ($vr !== true) {
                $this->session->set('errors', $lang->_('err_code_exists', $model->getCode()));
                return $this->getEditTemplateReult($model);
            }
        }

        $b = Helper::withTx(function ($db) use ($model) {
            return $model->save($db);
        });

        if (!$b) {
            $this->session->set('errors', $lang->_('err_system'));
            return $this->getEditTemplateReult($model);
        }
        return Web\Result::redirectResult($this->router->findHistory('list'));
    }

    public function deleteAction($id)
    {
        $lang = $this->context->localize;
        $m = UptownModel::load($id);

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
        $developers = DeveloperModel::find()->load()
            ->toArray(function ($item) {
                return array($item->getId(), $item->getName());
            });
        $existsIds = UptownModel::find()
            ->select('account_id')
            ->fetch()
            ->getColumnValues('account_id');
        $accounts = AccountModel::find()->load()
            ->filter(function ($item) use ($existsIds) {
                if (in_array('backend', $item->getDomains())) {
                    return false;
                }
                return !in_array($item->getId(), $existsIds);
            })->toArray(function ($item) {
                return array($item->getId(), $item->getUsername());
            });

        return Web\Result::templateResult(
            array('model' => $model, 'accounts' => $accounts, 'developers' => $developers),
            'void/realty/uptown/edit'
        );
    }
}