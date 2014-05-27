<?php
$this->assign('navigationBar', array(
    $this->html->anchor($this->locale->_('back'), $this->router->getHistoryUrl('list'))
));

$this->assign('toolbar', array(
    $this->html->button('button', $this->locale->_('save'), 'btn btn-primary')->setAttribute('data-submit', 'form1')
));

$f = $this->html->groupedForm()
        ->setAttribute('action', $this->router->buildUrl('save', '*'));
$f->beginGroup('tab_base', $this->locale->_('base_info'));
$f->newField($this->locale->_('system_parent_menu'), true,
    $this->html->treeSelect('parent_id', 'data[parent_id]', $this->model->getParentId(), $this->menus)
        ->setCaption($this->locale->_('admin_root_menu')));
$f->newField($this->locale->_('name'), true,
    $this->html->textbox('name', 'data[name]', $this->model->name)
        ->addValidateRule('required', true));
$f->newField($this->locale->_('url'), true,
    $this->html->textbox('url', 'data[url]', $this->model->getUrl()));
$f->newField($this->locale->_('user_behavior_list'), true,
    $this->html->optionList('behavior_codes', 'data[behavior_codes][]', $this->model->getBehaviorCodes(), $this->behaviors));
$f->newField($this->locale->_('enable'), true,
    $this->html->select('enabled', 'data[enabled]', $this->model->getEnabled(),
        array('1'=>$this->locale->_('yes'), '0'=>$this->locale->_('no')))
        ->addValidateRule('required', true));
$f->endGroup();

$f->addHidden('id', 'data[id]', $this->model->getId());
$this->assign('form', $f);
echo $this->includeTemplate('layout\form');