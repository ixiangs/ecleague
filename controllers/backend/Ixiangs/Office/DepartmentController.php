<?php
namespace Ixiangs\Office;

use Toy\Web;

class DepartmentController extends Web\Controller
{

    public function listAction()
    {
        $pi = $this->request->getParameter("pageindex", 1);
        $count = DepartmentModel::find()->count();
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
        $m = new DepartmentModel($this->request->getPost('data'));

        $vr = $m->validateProperties();
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
            $vr = $m->validateUnique();
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
        $m = DepartmentModel::load($id);

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
        return Web\Result::templateResult(
            array('model' => $model),
            'ixiangs/office/department/edit'
        );
    }
}