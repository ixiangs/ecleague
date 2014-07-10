<?php
namespace Components\Realty\Member;

use Components\Realty\Models\ComplaintModel;
use Components\Auth;
use Toy\Web;

class ComplaintController extends Web\Controller
{

    public function listAction()
    {
        $pi = $this->request->getParameter("pageindex", 1);
        $count = ComplaintModel::find()->fetchCount();
        $models = ComplaintModel::find()
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
        $model = ComplaintModel::load($id);
        return Web\Result::templateResult(array('model' => $model));
    }

}