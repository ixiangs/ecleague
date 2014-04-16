<?php
namespace Core\Admin\Backend;

use Toy\Web;

class MenuController extends Web\Controller
{

    private $_menus = null;
    private $_sortedMenus = array();

    public function listAction()
    {
        $this->_menus = \Tops::loadModel('admin/menu')->find()
            ->asc('parent_id')
            ->load();
        $this->sortMenus();
        return Web\Result::templateResult(array('menus' => $this->_sortedMenus));
    }

    private function sortMenus($parentId = 0, $level = 0)
    {
        for($i = 0; $i < count($this->_menus); $i++){
            $menu = $this->_menus[$i];
            if($menu->getParentId() == $parentId){
                $menu->setData('level', $level);
                $this->_sortedMenus[] = $menu;
                $this->sortMenus($menu->getId(), ++$level);
                --$level;
            }
        }
    }

    public function addAction()
    {
        $model = \Tops::loadModel('admin/menu');
        return $this->getEditTemplateResult($model);
    }

    public function addPostAction()
    {
        $locale = $this->context->locale;
        $m = \Tops::loadModel('admin/menu')->fillArray($this->request->getPost('data'));

        $vr = $m->validateProperties();
        if ($vr !== true) {
            $this->session->set('errors', $locale->_('err_input_invalid'));
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
        $m = \Tops::loadModel('admin/menu')->load($id);
        return $this->getEditTemplateResult($m);
    }

    public function editPostAction()
    {
        $locale = $this->context->locale;
        $m = \Tops::loadModel('admin/menu')
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
        $m = \Tops::loadModel('admin/attribute')->load($id);

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
        $locale = $this->context->locale;
        $lid = $locale->getCurrentLanguageId();
        $find = \Tops::loadModel('admin/menu')->find()
                    ->asc('parent_id');
        if($model->getId()){
            $find->ne('id', $model->getId());
        }

        $menus = $find->load()->toArray(function ($item) use($lid){
                        return array(null, array(
                            'id' => $item->getId(),
                            'parentId' => $item->getParentId(),
                            'value' => $item->getId(),
                            'text' => $item->names[$lid],
                        ));
                    });
        return Web\Result::templateResult(
            array('model' => $model, 'menus'=>$menus),
            'admin/menu/edit'
        );
    }
}