<?php
namespace Ixiangs\Attrs;

use Ixiangs\System\ComponentModel;
use Toy\Db\Helper;
use Toy\Web;

class AttributeGroupController extends Web\Controller
{

    public function listAction()
    {
        $pi = $this->request->getParameter("pageindex", 1);
        $count = GroupModel::find()->count();
        $models = GroupModel::find()
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
        return $this->getEditTemplateResult(new GroupModel());
    }

    public function editAction($id)
    {
        return $this->getEditTemplateResult(GroupModel::load($id));
    }

    public function savePostAction()
    {
        $locale = $this->context->locale;
        $m = new GroupModel($this->request->getPost('data'));

        $vr = $m->validateProperties();
        if ($vr !== true) {
            $this->session->set('errors', $locale->_('err_input_invalid'));
            return $this->getEditTemplateResult($m);
        }

        if ($m->getId()) {
            if (!$m->update()) {
                $this->session->set('errors', $locale->_('err_system'));
                return $this->getEditTemplateResult($m);
            }
        } else {
            if (!$m->insert()) {
                $this->session->set('errors', $locale->_('err_system'));
                return $this->getEditTemplateResult($m);
            }
        }

        return Web\Result::redirectResult($this->router->buildUrl('list'));
    }

    public function deleteAction($id)
    {
        $lang = $this->context->locale;
        $m = GroupModel::load($id);

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
        $model = GroupModel::load($id);
        $allAttributes = AttributeModel::find()
            ->eq('component_id', $model->getComponentId())
            ->eq('enabled', true)
            ->load();
        $selectedAttributes = $model->getAttributes();
        $unselectedAttributes = $allAttributes->filter(function($item) use($selectedAttributes){
            foreach($selectedAttributes as $sa){
                if($sa->getId() == $item->getId()){
                    return false;
                }
            }
            return true;
        });

        return Web\Result::templateResult(
            array('model' => $model,
                  'selectedAttributes'=>$selectedAttributes,
                  'unselectedAttributes'=>$unselectedAttributes)
        );
    }

    public function layoutPostAction($id){
        $attributeIds = $this->request->getPost('attribute_ids');
        $attributes = array();
        foreach($attributeIds as $index=>$attributeId){
            $attributes[] = array('id'=>$attributeId, 'position'=>$index + 1);
        }
        $model = GroupModel::load($id);
        $res = Helper::withTx(function($db) use($model, $attributes){
            $model->assignAttribute($attributes, $db);
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
        $components = ComponentModel::find()->load()
            ->toArray(function($item){
                return array($item->getId(), $item->getName());
            });
        return Web\Result::templateResult(
            array('model' => $model,
                'components'=>$components),
            'ixiangs/attrs/attribute-group/edit'
        );
    }
}