<?php
$this->assign('breadcrumb', array(
    $this->html->anchor($this->locale->_('user_manage')),
    $this->html->anchor($this->locale->_($this->router->action == 'add' ? "user_new_account" : "user_edit_account"))
));

$this->assign('navigationBar', array(
    $this->html->anchor($this->locale->_('back'), $this->router->buildUrl('list'))
));

$this->assign('toolbar', array(
    $this->html->button('button', $this->locale->_('save'), 'btn btn-primary')->setAttribute('data-submit', 'form1')
));

$f = $this->html->form();
if($this->router->action == 'add'):
$f->newField($this->locale->_('username'), true,
    $this->html->textbox('username', 'data[username]', $this->model->getUsername())
    ->addValidateRule('required', true));
else:
$f->addStaticField($this->locale->_('username'), $this->model->getUsername());
endif;
$f->newField($this->locale->_('email'), true,
    $this->html->textbox('email', 'data[email]', $this->model->getEmail(), 'email')
    ->addValidateRule('required', true));
if($this->router->action == 'add'):
$f->newField($this->locale->_('password'), true,
    $this->html->textbox('password', 'data[password]', '')
    ->addValidateRule('required', true));
endif;
$f->newField($this->locale->_('user_type'), true,
    $this->html->select('type', 'data[type]', $this->model->getLevel(), array(
        \Ixiangs\User\Constant::TYPE_ADMINISTRATOR=>$this->locale->_('user_type_admin'),
        \Ixiangs\User\Constant::TYPE_NORMAL=>$this->locale->_('user_type_normal')
    )));
$f->newField($this->locale->_('status'), true,
    $this->html->select('status', 'data[status]', $this->model->getStatus(), array(
        \Ixiangs\User\Constant::STATUS_ACCOUNT_ACTIVATED=>$this->locale->_('user_status_activated'),
        \Ixiangs\User\Constant::STATUS_ACCOUNT_NONACTIVATED=>$this->locale->_('user_status_nonactivated'),
        \Ixiangs\User\Constant::STATUS_ACCOUNT_DISABLED=>$this->locale->_('disabled')
    )));
$f->newField($this->locale->_('user_role_list'), true,
    $this->html->optionList('role_ids', 'data[role_ids][]', $this->model->getRoleIds(), $this->roles));
$f->addHidden('id', 'data[id]', $this->model->getId());
$this->assign('form', $f);
echo $this->includeTemplate('layout\form');