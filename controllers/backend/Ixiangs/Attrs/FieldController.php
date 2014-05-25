<?php
namespace Ixiangs\Attrs;

use Ixiangs\System\ComponentModel;
use Toy\Db\Helper;
use Toy\Web;

class FieldController extends Web\Controller
{

    public function listAction($entityid)
    {
        $entity = EntityModel::load($entityid);
        $fields = $entity->getFields()->load();
        return Web\Result::templateResult(array(
            'models' => $fields,
            'entity'=>$entity
        ));
    }

    public function savePostAction()
    {
//        $locale = $this->context->locale;
//        $data = $this->request->getPost('data');
//        $model = $data['id'] ? EntityModel::merge($data['id'], $data) : EntityModel::create($data);
//
//        $vr = $model->validateProperties();
//        if ($vr !== true) {
//            $this->session->set('errors', $locale->_('err_input_invalid'));
//            return $this->getEditTemplateResult($model);
//        }
//
//        if (!$model->save()) {
//            $this->session->set('errors', $locale->_('err_system'));
//            return $this->getEditTemplateResult($model);
//        }
//
//        return Web\Result::redirectResult($this->router->buildUrl('list'));
    }

    public function deleteAction($id)
    {
//        $lang = $this->context->locale;
//        $m = EntityModel::load($id);
//
//        if (!$m) {
//            $this->session->set('errors', $lang->_('err_system'));
//            return Web\Result::redirectResultt($this->router->buildUrl('list'));
//        }
//
//        if (!$m->delete()) {
//            $this->session->set('errors', $lang->_('err_system'));
//            return Web\Result::redirectResult($this->router->buildUrl('list'));
//        }
//        return Web\Result::redirectResult($this->router->buildUrl('list'));
    }


}