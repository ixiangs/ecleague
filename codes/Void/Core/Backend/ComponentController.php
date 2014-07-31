<?php
namespace Void\Core\Backend;

use Void\Core\ComponentModel;
use Toy\Web;

class ComponentController extends Web\Controller
{

    public function listAction()
    {
        $pi = $this->request->getParameter("pageindex", 1);
        $count = ComponentModel::find()->fetchCount();
        $models = ComponentModel::find()
            ->limit(PAGINATION_SIZE, ($pi - 1) * PAGINATION_SIZE)
            ->load();
        return Web\Result::templateResult(array(
                'models' => $models,
                'total' => $count,
                'pageIndex' => $pi)
        );
    }
}