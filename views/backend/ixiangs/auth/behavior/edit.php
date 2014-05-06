<?php
$this->assign('breadcrumb', array(
    $this->html->anchor($this->locale->_('auth_manage')),
    $this->html->anchor($this->locale->_('auth_behavior_list'), $this->router->buildUrl('list')),
    $this->html->anchor($this->locale->_($this->router->action == 'add' ? "add" : "edit"))
));

$this->assign('navigationBar', array(
        $this->html->anchor($this->locale->_('back'), $this->router->buildUrl('list'))
));

$this->assign('toolbar', array(
    $this->html->button('button', $this->locale->_('save'), 'btn btn-primary')->setAttribute('data-submit', 'form1')
));

$f = $this->html->form();
$f->addInputField('text', $this->locale->_('code'), 'code', 'code', $this->model->getCode())
    ->addValidateRule('required', true);
$f->addInputField('text', $this->locale->_('name'), 'name', 'name', $this->model->getName())
    ->addValidateRule('required', true);
$f->addInputField('text', $this->locale->_('url'), 'url', 'url', $this->model->getUrl());
$f->addSelectField(array(
        '0'=>$this->locale->_('no'),
        '1'=>$this->locale->_('yes')
    ),
    $this->locale->_('enable'), 'enabled', 'enabled', $this->model->getEnabled());
$this->assign('form', $f);
echo $this->includeTemplate('layout\form');