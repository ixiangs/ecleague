<?php
$this->assign('breadcrumb', array(
    $this->html->anchor($this->locale->_('user_manage')),
    $this->html->anchor($this->locale->_($this->router->action == 'add' ? "user_new_member" : "user_edit_member"))
));

$this->assign('navigationBar', array(
    $this->html->anchor($this->locale->_('back'), $this->router->buildUrl('list'))
));

$this->assign('toolbar', array(
    $this->html->button('button', $this->locale->_('save'), 'btn btn-primary')->setAttribute('data-submit', 'form1')
));

$f = $this->html->groupedForm();
$f->beginGroup('account_info', $this->locale->_('user_account_info'));
if($this->router->action == 'add'):
$f->addInputField('text', $this->locale->_('username'), 'username', 'account[username]', $this->account->getUsername())
    ->addValidateRule('required', true);
endif;
$f->addInputField('email', $this->locale->_('email'), 'email', 'account[email]', $this->account->getEmail())
    ->addValidateRule('required', true);
if($this->router->action == 'add'):
$f->addInputField('text', $this->locale->_('password'), 'password', 'account[password]', '')
    ->addValidateRule('required', true);
endif;
$f->endGroup();
$f->beginGroup('personal_info', $this->locale->_('user_personal_info'));
$f->addInputField('text', $this->locale->_('user_first_name'), 'first_name', 'member[first_name]', $this->member->getFirstName())
    ->addValidateRule('required', true);
$f->addInputField('text', $this->locale->_('user_last_name'), 'last_name', 'member[last_name]', $this->member->getLastName())
    ->addValidateRule('required', true);
$f->addInputField('text', $this->locale->_('gender'), 'gender', 'member[gender]', $this->member->getGender())
    ->addValidateRule('required', true);
$f->addInputField('email', $this->locale->_('email'), 'email', 'member[email]', $this->account->getEmail())
    ->addValidateRule('required', true);
$f->addInputField('text', $this->locale->_('mobile'), 'mobile', 'member[mobile]', $this->member->getMobile());
$f->endGroup();
$f->addHiddenField('id', 'id', $this->member->getId());
$this->assign('form', $f);
echo $this->includeTemplate('layout\form');