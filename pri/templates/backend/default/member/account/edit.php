<?php
$this->assign('breadcrumb', array(
    $this->html->anchor($this->locale->_('member_manage')),
    $this->html->anchor($this->locale->_($this->router->action == 'add' ? "member_new_member" : "member_edit_member"))
));

$this->assign('navigationBar', array(
    $this->html->anchor($this->locale->_('back'), $this->router->buildUrl('list'))
));

$this->assign('toolbar', array(
    $this->html->button('button', $this->locale->_('save'), 'btn btn-primary')->setAttribute('data-submit', 'form1')
));

$f = $this->html->groupedForm();
$f->beginGroup('account_info', $this->locale->_('member_account_info'));
if($this->router->action == 'add'):
$f->addInputField('text', $this->locale->_('username'), 'username', 'member[username]', $this->account->getUsername())
    ->addValidateRule('required', true);
$f->addInputField('text', $this->locale->_('password'), 'password', 'member[password]')
    ->addValidateRule('required', true);
endif;
$f->endGroup();
$f->beginGroup('personal_info', $this->locale->_('member_personal_info'));
$f->addInputField('text', $this->locale->_('user_first_name'), 'first_name', 'member[first_name]', $this->member->getFirstName())
    ->addValidateRule('required', true);
$f->addInputField('text', $this->locale->_('user_last_name'), 'last_name', 'member[last_name]', $this->member->getLastName())
    ->addValidateRule('required', true);
$f->addSelectField(array(1=>$this->locale->_('male'), 2=>$this->locale->_('female')),
                    $this->locale->_('gender'), 'gender', 'member[gender]', $this->member->getGender())
    ->addValidateRule('required', true)
    ->getInput()->setCaption('');
$f->addInputField('email', $this->locale->_('email'), 'email', 'member[email]', $this->account->getEmail())
    ->addValidateRule('required', true);
$f->addInputField('text', $this->locale->_('mobile'), 'mobile', 'member[mobile]', $this->member->getMobile());
$f->endGroup();
$f->addHiddenField('id', 'id', $this->member->getId());
$this->assign('form', $f);
echo $this->includeTemplate('layout\form');