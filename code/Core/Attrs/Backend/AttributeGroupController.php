<?php
namespace Core\Attrs\Backend;

use Ecleague\Tops;
use Toy\Data\Helper;
use Toy\Util\ArrayUtil;
use Toy\Web;

class AttributeGroupController extends Web\Controller
{

    public function listAction()
    {
        $pi = $this->request->getParameter("pageindex", 1);
        $count = Tops::loadModel('attrs/attributeGroup')->find()->selectCount()->execute()->getFirstValue();
        $models = Tops::loadModel('attrs/attributeGroup')->find()
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
        $model = Tops::loadModel('attrs/attributeGroup')
            ->setComponentId($this->request->getQuery('component_id'))
            ->setSetId($this->request->getQuery('set_id', 0));
        return $this->getEditTemplateResult($model);
    }

    public function editAction($id)
    {
        $m = Tops::loadModel('attrs/attributeGroup')->load($id);
        return $this->getEditTemplateResult($m);
    }

    public function savePostAction()
    {
        $locale = $this->context->locale;
        $m = Tops::loadModel('attrs/attributeGroup')->fillArray($this->request->getPost('data'));

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

        $as = Tops::loadModel('attrs/attributeSet')->load($this->request->getQuery('set_id'));
        $as->setGroupIds(array_merge($as->getGroupIds(array()), array($m->getId())));
        $as->update();

        return Web\Result::redirectResult($this->router->buildUrl(
            'attribute-set/groups',
            array('id' => $this->request->getQuery('set_id'))));
    }

//    public function editPostAction()
//    {
//        $locale = $this->context->locale;
//        $m = Tops::loadModel('attrs/attributeGroup')
//                ->merge($this->request->getPost('id'), $this->request->getPost('data'));
//        $vr = $m->validateProperties();
//        if ($vr !== true) {
//            $this->session->set('errors', $locale->_('err_input_invalid'));
//            return $this->getEditTemplateResult($m);
//        }
//
//        if (!$m->update()) {
//            $this->session->set('errors', $locale->_('err_system'));
//            return $this->getEditTemplateResult($m);
//        }
//
//        return Web\Result::redirectResult($this->router->buildUrl('list'));
//    }

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

    private function getEditTemplateResult($model)
    {
        $selectedAttributes = array();
        $unselectedAttributes = array();
        if ($model->getAttributeIds()) {
            $selectedAttributes = Tops::loadModel('attrs/attribute')
                ->find()
                ->eq('component_id', $model->getComponentId())
                ->eq('enabled', true)
                ->in('id', $model->getAttributeIds())
                ->load();
        }

        if ($model->getAttributeIds()) {
            $unselectedAttributes = Tops::loadModel('attrs/attribute')
                ->find()
                ->eq('component_id', $model->getComponentId())
                ->eq('enabled', true)
                ->notIn('id', $model->getAttributeIds())
                ->load();
        } else {
            $unselectedAttributes = Tops::loadModel('attrs/attribute')
                ->find()
                ->eq('component_id', $model->getComponentId())
                ->eq('enabled', true)
                ->load();
        }

        return Web\Result::templateResult(
            array('model' => $model, 'selectedAttributes'=>$selectedAttributes,
                'unselectedAttributes'=>$unselectedAttributes),
            'attrs/attribute-group/edit'
        );
    }
}