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

$f = $this->html->groupedForm()
        ->setAttribute('action', $this->router->buildUrl('save', '*'));
$f->beginGroup('tab_base', $this->locale->_('base_info'));
$f->newField($this->locale->_('admin_parent_menu'), true,
    $this->html->treeSelect('parent_id', 'data[parent_id]', $this->model->getParentId(), $this->menus)
        ->setCaption($this->locale->_('admin_root_menu')));
$f->newField($this->locale->_('url'), true,
    $this->html->textbox('url', 'data[url]', $this->model->getUrl()));
$f->newField($this->locale->_('user_behavior_list'), true,
    $this->html->optionList('behavior_codes', 'data[behavior_codes][]', $this->model->getBehaviorCodes(), $this->behaviors));
$f->newField($this->locale->_('enable'), true,
    $this->html->select('enabled', 'data[enabled]', $this->model->getEnabled(),
        array('1'=>$this->locale->_('yes'), '0'=>$this->locale->_('no')))
        ->addValidateRule('required', true));
$f->endGroup();

foreach($this->locale->getAllLanguages() as $lang):
    $f->beginGroup('tab_lang_'.$lang['code'], $lang['name']);
    $f->newField($this->locale->_('name'), true,
        $this->html->textbox('name_'.$lang['id'], 'data[name]['.$lang['id'].']', $this->model->name[$lang['id']])
        ->addValidateRule('required', true));
    $f->endGroup();
endforeach;

$f->addHidden('id', 'data[id]', $this->model->getId());
$this->assign('form', $f);
echo $this->includeTemplate('layout\form');