<?php
namespace Components\Content\Backend;

use Components\Content\Models\PublisherModel;
use Components\System\Models\ComponentModel;
use Components\Auth\Models\AccountModel;
use Toy\Web;

class PublisherController extends Web\Controller
{

    public function listAction()
    {
        $pi = $this->request->getParameter("pageindex", 1);
        $count = PublisherModel::find()->fetchCount();
        $models = PublisherModel::find()
            ->select(ComponentModel::propertyToField('name', 'component_name'))
            ->select(AccountModel::propertyToField('username'))
            ->join(ComponentModel::propertyToField('id'), PublisherModel::propertyToField('component_id'))
            ->join(AccountModel::propertyToField('id'), PublisherModel::propertyToField('account_id'))
            ->limit(PAGINATION_SIZE, ($pi - 1) * PAGINATION_SIZE)
            ->load();
        return Web\Result::templateResult(array(
                'models' => $models,
                'total' => $count)
        );
    }

    public function deleteAction($id)
    {
        $m = PublisherModel::load($id);

        if (!$m) {
            $this->session->set('errors', $this->localize->get('err_system'));
            return Web\Result::redirectResult($this->router->buildUrl('index'));
        }

        if (!$m->delete()) {
            $this->session->set('errors', $this->localize->get('err_system'));
            return Web\Result::redirectResultt($this->router->buildUrl('index'));
        }
        return Web\Result::redirectResult($this->router->buildUrl('list'));
    }
}