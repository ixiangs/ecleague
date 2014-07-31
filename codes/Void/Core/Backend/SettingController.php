<?php
namespace Void\Core\Backend;

use Void\Core\SettingModel;
use Toy\Web;

class SettingController extends Web\Controller
{

    public function editAction()
    {
        $model = SettingModel::load(1);
        return Web\Result::templateResult(array('model' => $model));
    }

    public function editPostAction()
    {
        $lang = $this->context->localize;
        $data = $this->request->getPost('data');
        $model = SettingModel::merge(1, $data);

        $vr = $model->validate();
        if ($vr !== true) {
            $this->session->set('errors', $lang->_('err_input_invalid'));
        }

        if (!$model->save()) {
            $this->session->set('errors', $lang->_('err_system'));
        }

        return Web\Result::templateResult(array('model' => $model));
    }
}