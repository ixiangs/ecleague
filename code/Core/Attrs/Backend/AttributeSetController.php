<?php
namespace Core\Attrs\Backend;

use Ecleague\Tops;
use Toy\Data\Helper;
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

    public function groupsAction($id)
    {
        $m = Tops::loadModel('attrs/attributeSet')->load($id);
        $groups = $m->getGroupAttributes();
        $attributes = Tops::loadModel('attrs/attribute')
                        ->find()
                        ->eq('component_code', 'ixiangs_catalogue')
                        ->load();
        return Web\Result::templateResult(array(
            'model' => $m,
            'groups'=>$groups,
            'attributes'=>$attributes
        ));
    }

    public function addAction()
    {
        $model = Tops::loadModel('attrs/attributeSet');
        return $this->getEditTemplateResult($model);
    }

    public function addPostAction()
    {
        $locale = $this->context->locale;
        $m = Tops::loadModel('attrs/attributeSet')->fillArray($this->request->getPost('data'));

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
        $m = Tops::loadModel('attrs/attributeSet');
        $m->load($id);
        return $this->getEditTemplateResult($m);
    }

    public function editPostAction()
    {
        $locale = $this->context->locale;
        $m = Tops::loadModel('attrs/attributeSet')
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
        $lid = $this->context->locale->getCurrentLanguageId();
        $attrs = Tops::loadModel('attrs/attributeGroup')->find()->load()
                    ->toArray(function($item) use($lid){
                        return array($item->getId(), $item->name[$lid].'('.$item->memo[$lid].')');
                    });
        $coms = Tops::loadModel('admin/component')
            ->find()->execute()->combineColumns('code', 'name');
        return Web\Result::templateResult(
            array('model' => $model, 'attributes'=>$attrs, 'components'=>$coms),
            'attrs/attribute-set/edit'
        );
    }
}