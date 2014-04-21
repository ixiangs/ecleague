<?php
namespace Core\Auth\Backend;

use Toy\Web;
use Auth\Model\RoleModel, Auth\Model\BehaviorModel;

class RoleController extends Web\Controller
{

    public function listAction()
    {
        $pi = $this->request->getParameter("pageindex", 1);
        $count = \Ecleague\Tops::loadModel('auth/role')->find()->selectCount()->execute()->getFirstValue();
        $models = \Ecleague\Tops::loadModel('auth/role')->find()->limit(PAGINATION_SIZE, ($pi - 1) * PAGINATION_SIZE)->load();
        return Web\Result::templateResult(array(
                'models' => $models,
                'behaviors' => \Ecleague\Tops::loadModel('auth/behavior')->find()->execute()->combineColumns('id', 'code'),
                'total' => $count)
        );
    }

    public function addAction()
    {
        return $this->getEditTemplateReult(\Ecleague\Tops::loadModel('auth/role'));
    }

    public function addPostAction()
    {
        $lang = $this->context->locale;
        $m = \Ecleague\Tops::loadModel('auth/role', $this->request->getAllParameters());

        $vr = $m->validateProperties();
        if ($vr !== true) {
            $this->session->set('errors', $this->_('err_input_invalid'));
            return $this->getEditTemplateReult($m);
        }

        $vr = $m->validateUnique();
        if ($vr !== true) {
            $this->session->set('errors', $lang->_('err_code_exists', $m->getCode()));
            return $this->getEditTemplateReult($m);
        }

        if (!$m->insert()) {
            $this->session->set('errors', $this->_('err_system'));
            return $this->getEditTemplateReult($m);;
        }

        return Web\Result::redirectResult($this->router->buildUrl('list'));
    }

    public function editAction($id)
    {
        $m = \Ecleague\Tops::loadModel('auth/role')->load($id);
        return $this->getEditTemplateReult($m);
    }

    public function editPostAction()
    {
        $lang = $this->context->locale;
        $m = \Ecleague\Tops::loadModel('auth/role')->merge($this->request->getParameter('id'), $this->request->getAllParameters());
        $m->setBehaviorIds($this->request->getParameter('behavior_ids', array()));
        $vr = $m->validateProperties();
        if ($vr !== true) {
            $this->session->set('errors', $lang->_('err_input_invalid'));
            return $this->getEditTemplateReult($m);
        }

        if (!$m->update()) {
            $this->session->set('errors', $lang->_('err_system'));
            return $this->getEditTemplateReult($m);
        }

        return Web\Result::redirectResult($this->router->buildUrl('list'));
    }

    public function deleteAction($id)
    {
        $m = \Ecleague\Tops::loadModel('auth/role')->load($id);

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
            array(
                'model' => $model,
                'behaviors' => \Ecleague\Tops::loadModel('auth/behavior')->find()->asc('code')->execute()->combineColumns('id', 'name')),
            'auth/role/edit'
        );
    }
}