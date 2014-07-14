<?php
namespace Void\Realty\Member;

use Void\Realty\RepairModel;
use Void\Auth;
use Toy\Web;

class RepairController extends Web\Controller
{

    public function listAction()
    {
        $pi = $this->request->getParameter("pageindex", 1);
        $count = RepairModel::find()->fetchCount();
        $models = RepairModel::find()
            ->limit(PAGINATION_SIZE, ($pi - 1) * PAGINATION_SIZE)
            ->load();
        return Web\Result::templateResult(array(
                'models' => $models,
                'total' => $count,
                'pageIndex' => $pi)
        );
    }

    public function detailAction($id)
    {
        $model = RepairModel::load($id);
        return Web\Result::templateResult(array('model' => $model));
    }

}