<?php
namespace Core\Dass\Backend;

use Toy\Data\Helper;
use Toy\Util\ArrayUtil;
use Toy\Web;

class AttributeGroupController extends Web\Controller
{

    public function listAction()
    {
        $pi = $this->request->getParameter("pageindex", 1);
        $count = \Tops::loadModel('dass/attributeGroup')->find()->selectCount()->execute()->getFirstValue();
        $models = \Tops::loadModel('dass/attributeGroup')->find()
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
        $model = \Tops::loadModel('dass/attributeGroup');
        return $this->getEditTemplateResult($model);
    }

    public function addPostAction()
    {
        $locale = $this->context->locale;
        $m = \Tops::loadModel('dass/attributeGroup')->fillArray($this->request->getPost('data'));

        $vr = $m->validateProperties();
        if ($vr !== true) {
            $this->session->set('errors', $locale->_('err_input_invalid'));
            return $this->getEditTemplateResult($m);
        }

        if ($m->validateUnique() !== true) {
            $this->session->set('errors', $locale->_('dass_err_attribute_group_exists', $m->getCode()));
            return $this->getEditTemplateResult($m);
        }

        if (!$m->insert()) {
            $this->session->set('errors', $locale->_('err_system'));
            return $this->getEditTemplateResult($m);
        }

        return Web\Result::redirectResult($this->router->buildUrl('list'));
    }

    public function editAction($id)
    {
        $m = \Tops::loadModel('dass/attributeGroup');
        $m->load($id);
        return $this->getEditTemplateResult($m);
    }

    public function editPostAction()
    {
        $locale = $this->context->locale;
        $m = \Tops::loadModel('dass/attributeGroup')
                ->merge($this->request->getPost('id'), $this->request->getPost('data'));
        $vr = $m->validateProperties();
        if ($vr !== true) {
            $this->session->set('errors', $locale->_('err_input_invalid'));
            return $this->getEditTemplateResult($m);
        }

        if (!$m->update()) {
            $this->session->set('errors', $locale->_('err_system'));
            return $this->getEditTemplateResult($m);
        }

        return Web\Result::redirectResult($this->router->buildUrl('list'));
    }

    public function deleteAction($id)
    {
        $lang = $this->context->locale;
        $m = \Tops::loadModel('dass/attribute')->load($id);

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
        $lid = $this->context->locale->getCurrentLanguageId();
        $attrs = \Tops::loadModel('dass/attribute')->find()->load()
                    ->toArray(function($item) use($lid){
                        return array($item->getId(), $item->display_labels[$lid]);
                    });
        return Web\Result::templateResult(
            array('model' => $model, 'attributes'=>$attrs),
            'dass/attribute-group/edit'
        );
    }
}