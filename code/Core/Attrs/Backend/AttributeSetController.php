<?php
namespace Core\Attrs\Backend;

use Toy\Data\Helper;
use Toy\Util\ArrayUtil;
use Toy\Web;

class AttributeSetController extends Web\Controller
{

    public function listAction()
    {
        $pi = $this->request->getParameter("pageindex", 1);
        $count = \Ecleague\Tops::loadModel('attrs/attributeSet')->find()->selectCount()->execute()->getFirstValue();
        $models = \Ecleague\Tops::loadModel('attrs/attributeSet')->find()
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
        $model = \Ecleague\Tops::loadModel('attrs/attributeSet');
        return $this->getEditTemplateResult($model);
    }

    public function addPostAction()
    {
        $locale = $this->context->locale;
        $m = \Ecleague\Tops::loadModel('attrs/attributeSet')->fillArray($this->request->getPost('data'));

        $vr = $m->validateProperties();
        if ($vr !== true) {
            $this->session->set('errors', $locale->_('err_input_invalid'));
            return $this->getEditTemplateResult($m);
        }

        if ($m->validateUnique() !== true) {
            $this->session->set('errors', $locale->_('attrs_err_attribute_set_exists', $m->getCode()));
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
        $m = \Ecleague\Tops::loadModel('attrs/attributeSet');
        $m->load($id);
        return $this->getEditTemplateResult($m);
    }

    public function editPostAction()
    {
        $locale = $this->context->locale;
        $m = \Ecleague\Tops::loadModel('attrs/attributeSet')
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
        $m = \Ecleague\Tops::loadModel('attrs/attribute')->load($id);

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
        $attrs = \Ecleague\Tops::loadModel('attrs/attributeGroup')->find()->load()
                    ->toArray(function($item) use($lid){
                        return array($item->getId(), $item->names[$lid]);
                    });
        return Web\Result::templateResult(
            array('model' => $model, 'attributes'=>$attrs),
            'attrs/attribute-set/edit'
        );
    }
}