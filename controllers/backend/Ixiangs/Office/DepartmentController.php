<?php
namespace Ixiangs\Office;

use Ixiangs\Entities\EntityModel;
use Toy\Web;

class DepartmentController extends Web\Controller
{

    public function listAction()
    {
        $pi = $this->request->getParameter("pageindex", 1);
        $count = DepartmentModel::find()->executeCount();
        $models = DepartmentModel::find()->limit(PAGINATION_SIZE, ($pi - 1) * PAGINATION_SIZE)->load();
        return Web\Result::templateResult(array(
                'models' => $models,
                'total' => $count)
        );
    }

    public function addAction()
    {
        return $this->getEditTemplateReult(new DepartmentModel());
    }

    public function editAction($id)
    {
        return $this->getEditTemplateReult(DepartmentModel::load($id));
    }

    public function savePostAction()
    {
        $lang = $this->context->locale;
        $data = $this->request->getPost('data');
        $model = $data['id'] ? DepartmentModel::merge($data['id'], $data) : DepartmentModel::create($data);

        $vr = $model->validateProperties();
        if ($vr !== true) {
            $this->session->set('errors', $lang->_('err_input_invalid'));
            return $this->getEditTemplateReult($model);
        }

        if (!$model->save()) {
            $this->session->set('errors', $lang->_('err_system'));
            return $this->getEditTemplateReult($model);
        }

        return Web\Result::redirectResult($this->router->getHistoryUrl('list'));
    }

    public function deleteAction($id)
    {
        $model = DepartmentModel::load($id);

        if ($model) {
            if (!$model->delete()) {
                $this->session->set('errors', $this->languages->get('err_system'));
            }
        } else {
            $this->session->set('errors', $this->languages->get('err_system'));
        }

        return Web\Result::redirectResult($this->router->getHistoryUrl('index'));
    }

    private function getEditTemplateReult($model)
    {
        return Web\Result::templateResult(
            array('model' => $model),
            'ixiangs/office/department/edit'
        );
    }
}