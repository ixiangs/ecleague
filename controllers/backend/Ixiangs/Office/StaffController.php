<?php
namespace Ixiangs\Office;

use Toy\Web;

class StaffController extends Web\Controller
{

    public function listAction()
    {
        $pi = $this->request->getParameter("pageindex", 1);
        $count = StaffModel::find()->executeCount();
        $models = StaffModel::find()
            ->select(Constant::TABLE_STAFF.'.*', Constant::TABLE_DEPARTMENT.'.name as department_name',
                Constant::TABLE_POSITION.'.name as position_name')
            ->join(Constant::TABLE_DEPARTMENT, Constant::TABLE_DEPARTMENT.'.id', Constant::TABLE_STAFF.'.department_id')
            ->join(Constant::TABLE_POSITION, Constant::TABLE_POSITION.'.id', Constant::TABLE_STAFF.'.position_id')
            ->limit(PAGINATION_SIZE, ($pi - 1) * PAGINATION_SIZE)->load();
        return Web\Result::templateResult(array(
                'models' => $models,
                'total' => $count)
        );
    }

    public function addAction()
    {
        return $this->getEditTemplateReult(new StaffModel());
    }

    public function editAction($id)
    {
        return $this->getEditTemplateReult(StaffModel::load($id));
    }

    public function savePostAction()
    {
        $lang = $this->context->localize;
        $m = new StaffModel($this->request->getPost('data'));

        $vr = $m->validate();
        if ($vr !== true) {
            $this->session->set('errors', $lang->_('err_input_invalid'));
            return $this->getEditTemplateReult($m);
        }

        if ($m->getId()) {
            if (!$m->update()) {
                $this->session->set('errors', $lang->_('err_system'));
                return $this->getEditTemplateReult($m);
            }
        } else {
            $vr = $m->checkUnique();
            if ($vr !== true) {
                $this->session->set('errors', $lang->_('err_code_exists', $m->getCode()));
                return $this->getEditTemplateReult($m);
            }

            if (!$m->insert()) {
                $this->session->set('errors', $this->_('err_system'));
                return $this->getEditTemplateReult($m);;
            }
        }

        return Web\Result::redirectResult($this->router->buildUrl('list'));
    }

    public function deleteAction($id)
    {
        $m = StaffModel::load($id);

        if (!$m) {
            $this->session->set('errors', $this->languages->get('err_system'));
            return Web\Result::redirectResult($this->router->buildUrl('index'));
        }

        if (!$m->delete()) {
            $this->session->set('errors', $this->languages->get('err_system'));
            return Web\Result::redirectResultt($this->router->buildUrl('index'));
        }
        return Web\Result::redirectResult($this->router->buildUrl('index'));
    }

    private function getEditTemplateReult($model)
    {
        $positions = PositionModel::find()
            ->load()
            ->toArray(function ($item) {
                return array(null, array(
                    'id' => $item->getId(),
                    'parentId' => $item->getParentId(),
                    'value' => $item->getId(),
                    'text' => $item->name,
                ));
            });
        $departments = DepartmentModel::find()->load()
            ->toArray(function ($item) {
                return array($item->getId(), $item->name);
            });
        return Web\Result::templateResult(
            array('model' => $model, 'positions' => $positions, 'departments'=>$departments),
            'ixiangs/office/staff/edit'
        );
    }
}