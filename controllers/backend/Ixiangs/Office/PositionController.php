<?php
namespace Ixiangs\Office;

use Toy\Web;

class PositionController extends Web\Controller
{

    public function listAction()
    {
        $pi = $this->request->getParameter("pageindex", 1);
        $count = PositionModel::find()->executeCount();
        $models = PositionModel::find()->limit(PAGINATION_SIZE, ($pi - 1) * PAGINATION_SIZE)->load();
        return Web\Result::templateResult(array(
                'models' => $models,
                'total' => $count)
        );
    }

    public function addAction()
    {
        return $this->getEditTemplateReult(new PositionModel());
    }

    public function editAction($id)
    {
        return $this->getEditTemplateReult(PositionModel::load($id));
    }


    public function savePostAction()
    {
        $lang = $this->context->locale;
        $m = new PositionModel($this->request->getPost('data'));

        $vr = $m->validate();
        if ($vr !== true) {
            $this->session->set('errors', $this->_('err_input_invalid'));
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
        $m = PositionModel::load($id);

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
            ->ne('id', $model->getId(0))
            ->load()
            ->toArray(function ($item) {
                return array(null, array(
                    'id' => $item->getId(),
                    'parentId' => $item->getParentId(),
                    'value' => $item->getId(),
                    'text' => $item->name,
                ));
            });
        return Web\Result::templateResult(
            array('model' => $model, 'positions' => $positions),
            'ixiangs/office/position/edit'
        );
    }
}