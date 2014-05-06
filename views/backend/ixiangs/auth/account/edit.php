<?php
$this->assign('breadcrumb', array(
    $this->html->anchor($this->locale->_('auth_manage')),
    $this->html->anchor($this->locale->_('auth_account_list'), $this->router->buildUrl('list')),
    $this->html->anchor($this->locale->_($this->router->action == 'add' ? "add" : "edit"))
));

$this->assign('navigationBar', array(
    $this->html->anchor($this->locale->_('back'), $this->router->buildUrl('list'))
));

$this->assign('toolbar', array(
    $this->html->button('button', $this->locale->_('save'), 'btn btn-primary')->setAttribute('data-submit', 'form1')
));

$f = $this->html->form();
if($this->router->action == 'add'):
$f->addInputField('text', $this->locale->_('username'), 'username', 'username', $this->model->getUsername())
    ->addValidateRule('required', true);
else:
$f->addStaticField($this->locale->_('username'), $this->model->getUsername());
endif;
$f->addInputField('email', $this->locale->_('email'), 'email', 'email', $this->model->getEmail())
    ->addValidateRule('required', true);
if($this->router->action == 'add'):
$f->addInputField('text', $this->locale->_('password'), 'password', 'password', '')
    ->addValidateRule('required', true);
endif;
$f->addSelectField(array(
        \Ixiangs\User\AccountModel::LEVEL_ADMINISTRATOR=>$this->locale->_('auth_level_admin'),
        \Ixiangs\User\AccountModel::LEVEL_NORMAL=>$this->locale->_('auth_level_normal')
), $this->locale->_('level'), 'level', 'level', $this->model->getLevel());
$f->addSelectField(array(
        \Ixiangs\User\AccountModel::STATUS_ACTIVATED=>$this->locale->_('auth_status_activated'),
        \Ixiangs\User\AccountModel::STATUS_NONACTIVATED=>$this->locale->_('auth_status_nonactivated'),
        \Ixiangs\User\AccountModel::STATUS_DISABLED=>$this->locale->_('disabled')
), $this->locale->_('status'), 'status', 'status', $this->model->getStatus());
$f->addCheckboxListField($this->roles, $this->locale->_('auth_role_list'), 'role_ids', 'role_ids[]', $this->model->getRoleIds());
$f->addHidden('id', 'id', $this->model->getId());
$this->assign('form', $f);
echo $this->includeTemplate('layout\form');