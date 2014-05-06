<?php
namespace Ixiangs\Attrs;

use Ecleague\Tops;
use Toy\Db\Helper;
use Toy\Util\ArrayUtil;
use Toy\Web;

class AttributeSetController extends Web\Controller
{

    public function listAction()
    {
        $pi = $this->request->getParameter("pageindex", 1);
        $count = Tops::loadModel('attrs/attributeSet')->find()->selectCount()->execute()->getFirstValue();
        $models = Tops::loadModel('attrs/attributeSet')->find()
            ->asc('code')
            ->limit(PAGINATION_SIZE, ($pi - 1) * PAGINATION_SIZE)
            ->load();
        return Web\Result::templateResult(array(
                'models' => $models,
                'total' => $count,
                'pageIndex' => $pi)
        );
    }

    public function addAction()
    {
        $model = Tops::loadModel('attrs/attributeSet');
        return $this->getEditTemplateResult($model);
    }

    public function savePostAction()
    {
        $locale = $this->context->locale;
        $m = Tops::loadModel('attrs/attributeSet')->fillArray($this->request->getPost('data'));

        $vr = $m->validateProperties();
        if ($vr !== true) {
            $this->session->set('errors', $locale->_('err_input_invalid'));
            return $this->getEditTemplateResult($m);
        }

        if($m->getId()){
            if (!$m->merge()->update()) {
                $this->session->set('errors', $locale->_('err_system'));
                return $this->getEditTemplateResult($m);
            }
            return Web\Result::redirectResult($this->router->buildUrl('groups', array('id'=>$m->getId())));
        }else{
            if (!$m->insert()) {
                $this->session->set('errors', $locale->_('err_system'));
                return $this->getEditTemplateResult($m);
            }
            return Web\Result::redirectResult($this->router->buildUrl('layout', array('id'=>$m->getId())));
        }
    }

    public function editAction($id)
    {
        $m = Tops::loadModel('attrs/attributeSet')->load($id);
        return $this->getEditTemplateResult($m);
    }

    public function deleteAction($id)
    {
        $lang = $this->context->locale;
        $m = Tops::loadModel('attrs/attribute')->load($id);

        if (!$m) {
            $this->session->set('errors', $lang->_('err_system'));
            return Web\Result::redirectResultt($this->router->buildUrl('list'));
        }

        if (!$m->delete()) {
            $this->session->set('errors', $lang->_('err_system'));
            return Web\Result::redirectResult($this->router->buildUrl('list'));
        }
        return Web\Result::redirectResult($this->router->buildUrl('list'));
    }

    public function layoutAction($id){
        $model = Tops::loadModel('attrs/attributeSet')->load($id);
        $allGroups = Tops::loadModel('attrs/attributeGroup')
            ->find()
            ->eq('component_id', $model->getComponentId())
            ->eq('enabled', true)
            ->load();
        $assignedGroups = $model->getGroups();
        $unassignedGroups = $allGroups->filter(function($item) use($assignedGroups){
            foreach($assignedGroups as $sa){
                if($sa->getId() == $item->getId()){
                    return false;
                }
            }
            return true;
        });

        return Web\Result::templateResult(
            array('model' => $model,
                'selectedGroups'=>$assignedGroups,
                'unselectedGroups'=>$unassignedGroups)
        );
    }

    public function layoutPostAction($id){
        $groupIds = $this->request->getPost('group_ids');
        $groups = array();
        foreach($groupIds as $index=>$groupId){
            $groups[] = array('id'=>$groupId, 'position'=>$index + 1);
        }
        $model = Tops::loadModel('attrs/attributeSet')->load($id);
        $res = Helper::withTx(function($db) use($model, $groups){
            $model->assignGroup($groups, $db);
            return true;
        });

        if (!$res) {
            $this->session->set('errors', $this->context->locale->_('err_system'));
            return $this->layoutAction($id);
        }

        return Web\Result::redirectResult($this->router->buildUrl('list'));
    }


    private function getEditTemplateResult($model)
    {
        $components = Tops::loadModel('admin/component')
            ->find()
            ->execute()
            ->combineColumns('id', 'name');
        return Web\Result::templateResult(
            array('model' => $model,
                    'components'=>$components),
            'attrs/attribute-set/edit'
        );
    }
}