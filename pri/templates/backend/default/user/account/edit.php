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

$f = $this->html->groupedForm();
$f->beginGroup('account_info', $this->locale->_('user_account_info'));
if($this->router->action == 'add'):
$f->newField($this->locale->_('username'), true,
    $this->html->textbox('username', 'member[username]', $this->model->getUsername())
    ->addValidateRule('required', true));
$f->newField($this->locale->_('password'), true,
    $this->html->textbox('password', 'member[password]'))
    ->addValidateRule('required', true);
endif;
$f->endGroup();
$f->beginGroup('personal_info', $this->locale->_('user_personal_info'));
$f->newField($this->locale->_('user_first_name'), true,
    $this->html->textbox('first_name', 'member[first_name]', $this->model->getFirstName())
    ->addValidateRule('required', true));
$f->newField($this->locale->_('user_last_name'), true,
    $this->html->textbox('last_name', 'member[last_name]', $this->model->getLastName())
    ->addValidateRule('required', true));
$f->newField($this->locale->_('gender'), true,
    $this->html->select('gender', 'member[gender]', $this->model->getGender(),
        array(1=>$this->locale->_('male'), 2=>$this->locale->_('female')))
    ->addValidateRule('required', true)
    ->setCaption(''));
$f->newField($this->locale->_('email'), true,
    $this->html->textbox('email', 'member[email]', $this->model->getEmail()))
    ->addValidateRule('required', true);
$f->newField($this->locale->_('mobile'), false,
    $this->html->textbox('mobile', 'member[mobile]', $this->model->getMobile()));
$f->endGroup();
$f->addHidden('id', 'data[id]', $this->model->getId());
$this->assign('form', $f);
echo $this->includeTemplate('layout\form');