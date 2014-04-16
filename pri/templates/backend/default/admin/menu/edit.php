<?php
$this->assign('breadcrumb', array(
    $this->html->anchor($this->locale->_('admin_menu_manage')),
    $this->router->action == 'add'? $this->html->anchor($this->locale->_('admin_add_menu')):
        $this->html->anchor($this->locale->_('admin_edit_menu'))
));

$this->assign('navigationBar', array(
    $this->html->anchor($this->locale->_('back'), $this->router->buildUrl('list'))
));

$this->assign('toolbar', array(
    $this->html->button('button', $this->locale->_('save'), 'btn btn-primary')->setAttribute('data-submit', 'form1')
));

$f = $this->html->groupedForm();
$f->beginGroup('tab_base', $this->locale->_('base_info'));
$f->addTreeSelectField($this->menus, $this->locale->_('admin_parent_menu'), 'parent_id', 'data[parent_id]', $this->model->getParentId())
    ->getInput()->setCaption($this->locale->_('admin_root_menu'));
$f->addInputField('text', $this->locale->_('url'), 'url', 'data[url]', $this->model->getUrl());
$f->addSelectField(array('1'=>$this->locale->_('yes'), '0'=>$this->locale->_('no')),
    $this->locale->_('enable'), 'enabled', 'data[enabled]', $this->model->getEnabled());
$f->endGroup();

foreach($this->locale->getLanguages() as $lang):
    $f->beginGroup('tab_lang_'.$lang['code'], $lang['name']);
    $f->addInputField('text', $this->locale->_('name'), 'name_'.$lang['id'], 'data[names]['.$lang['id'].']', $this->model->names[$lang['id']])
        ->addValidateRule('required', true);
    $f->endGroup();
endforeach;

$f->addHiddenField('id', 'id', $this->model->getId());
$this->assign('form', $f);
echo $this->includeTemplate('layout\form');