<?php
$this->assign('breadcrumb', array(
    array('text'=>$this->locale->_('auth_manage')),
    array('text'=>$this->locale->_('auth_account_list'), 'url'=>$this->router->buildUrl('list')),
    array('text'=>$this->locale->_($this->router->action == 'add' ? "add" : "edit"), 'active'=>true)
));

$this->assign('buttons', array(
    array('text'=>$this->locale->_('back'), 'url'=>$this->router->buildUrl('list'))
));

$f = $this->html->form();
$f->addInputField('text', $this->locale->_('username'), 'username', 'username', $this->model->getUsername())
    ->addValidateRule('required', true);
$f->addInputField('email', $this->locale->_('email'), 'email', 'email', $this->model->getEmail())
    ->addValidateRule('required', true);
if($this->router->action == 'add'):
$f->addInputField('text', $this->locale->_('password'), 'password', 'password', '')
    ->addValidateRule('required', true);
endif;
$f->addSelectField(array(
        \Core\Auth\Model\AccountModel::LEVEL_ADMINISTRATOR=>$this->locale->_('auth_level_admin'),
        \Core\Auth\Model\AccountModel::LEVEL_NORMAL=>$this->locale->_('auth_level_normal')
), $this->locale->_('level'), 'level', 'level', $this->model->getLevel());
$f->addSelectField(array(
        \Core\Auth\Model\AccountModel::STATUS_ACTIVATED=>$this->locale->_('auth_status_activated'),
        \Core\Auth\Model\AccountModel::STATUS_NONACTIVATED=>$this->locale->_('auth_status_nonactivated'),
        \Core\Auth\Model\AccountModel::STATUS_DISABLED=>$this->locale->_('disabled')
), $this->locale->_('status'), 'status', 'status', $this->model->getStatus());
$f->addCheckboxesField($this->roles, $this->locale->_('auth_role_list'), 'role_ids', 'role_ids[]', $this->model->getRoleIds());
$f->addHiddenField('id', 'id', $this->model->getId());
$f->addButton('submit', $this->locale->_('save'), 'btn btn-primary');
$this->assign('form', $f);
echo $this->includeTemplate('layout\form');