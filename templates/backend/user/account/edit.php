<?php
$this->assign('navigationBar', array(
    $this->html->anchor($this->localize->_('back'), $this->router->findHistory('list'))
));

$this->assign('toolbar', array(
    $this->html->button('button', $this->localize->_('save'), 'btn btn-primary')->setAttribute('data-submit', 'form1')
));

$f = $this->html->form()
            ->setAttribute('action', $this->router->buildUrl('save'));
if($this->router->action == 'add'):
$f->newField($this->localize->_('username'), true,
    $this->html->textbox('username', 'data[username]', $this->model->getUsername())
    ->addValidateRule('required', true));
else:
$f->addStaticField($this->localize->_('username'), $this->model->getUsername());
endif;
$f->newField($this->localize->_('email'), true,
    $this->html->textbox('email', 'data[email]', $this->model->getEmail(), 'email')
    ->addValidateRule('required', true));
if($this->router->action == 'add'):
$f->newField($this->localize->_('password'), true,
    $this->html->textbox('password', 'data[password]', '')
    ->addValidateRule('required', true));
endif;
$f->newField($this->localize->_('user_type'), true,
    $this->html->select('type', 'data[type]', $this->model->getLevel(), array(
        \Codes\User\Models\Constant::TYPE_ADMINISTRATOR=>$this->localize->_('user_type_admin'),
        \Codes\User\Models\Constant::TYPE_NORMAL=>$this->localize->_('user_type_normal')
    )));
$f->newField($this->localize->_('status'), true,
    $this->html->select('status', 'data[status]', $this->model->getStatus(), array(
        \Codes\User\Models\Constant::STATUS_ACCOUNT_ACTIVATED=>$this->localize->_('user_status_activated'),
        \Codes\User\Models\Constant::STATUS_ACCOUNT_NONACTIVATED=>$this->localize->_('user_status_nonactivated'),
        \Codes\User\Models\Constant::STATUS_ACCOUNT_DISABLED=>$this->localize->_('disabled')
    )));
$f->newField($this->localize->_('user_role_list'), true,
    $this->html->optionList('role_ids', 'data[role_ids][]', $this->model->getRoleIds(), $this->roles));
$f->addHidden('id', 'data[id]', $this->model->getId());
$this->assign('form', $f);
echo $this->includeTemplate('layout\form');