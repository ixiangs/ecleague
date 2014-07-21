<?php
namespace Void\Realty\Member;

use Void\Realty\RepairModel;
use Void\Auth;
use Toy\Web;
use Void\Realty\StaffModel;

class RepairController extends Web\Controller
{

    public function listAction()
    {
        $pi = $this->request->getParameter("pageindex", 1);
        $count = RepairModel::find()
            ->eq('uptown_id', $this->session->get('uptownId'))
            ->fetchCount();
        $models = RepairModel::find()
            ->select(StaffModel::propertyToField('name', 'repairer_name'))
            ->join(StaffModel::propertyToField('id'), RepairModel::propertyToField('repairer_id'), 'left')
            ->eq(RepairModel::propertyToField('uptown_id'), $this->session->get('uptownId'))
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
        $repairers = array();
        $model = RepairModel::load($id);
        if(!$model->repairer_id){
            $repairers = StaffModel::find()
                ->eq('uptown_id', $this->session->get('uptownId'))
                ->eq('deleted', 0)
                ->fetch()
                ->combineColumns('id', 'name');
        }else{
            $staff = StaffModel::load($model->getRepairerId());
            $model->setRepairerName($staff->getName());
        }
        return Web\Result::templateResult(array(
            'model' => $model,
            'repairers'=>$repairers));
    }

    public function detailPostAction($id)
    {
        $model = RepairModel::load($id);
        if(!$model->repairer_id){
            $model->setRepairerId($this->request->getPost('repairer_id'))
                ->setRepairTime(time())
                ->Save();
        }
        return Web\Result::redirectResult($this->router->buildUrl('detail', array(
            'id'=>$id
        )));
    }
}