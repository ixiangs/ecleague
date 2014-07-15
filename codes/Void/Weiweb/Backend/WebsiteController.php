<?php
namespace Void\Observer\Backend;

use Toy\Web;
use Void\Observer\WebsiteModel;

class WebsiteController extends Web\Controller
{

    public function listAction()
    {
        $pi = $this->request->getParameter("pageindex", 1);
        $count = WebsiteModel::find()->fetchCount();
        $models = WebsiteModel::find()
            ->limit(PAGINATION_SIZE, ($pi - 1) * PAGINATION_SIZE)
            ->load();
        return Web\Result::templateResult(array(
                'models' => $models,
                'total' => $count)
        );
    }

    public function deleteAction($id)
    {
        $m = WebsiteModel::load($id);

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