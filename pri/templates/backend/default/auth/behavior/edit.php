<?php
$this->assign('breadcrumb', array(
    array('text'=>$this->locale->_('auth_manage')),
    array('text'=>$this->locale->_('auth_behavior_list'), 'url'=>$this->router->buildUrl('list')),
    array('text'=>$this->locale->_($this->router->action == 'add' ? "add" : "edit"), 'active'=>true)
));

$this->assign('buttons', array(
    array('text'=>$this->locale->_('back'), 'url'=>$this->router->buildUrl('list'))
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
$f->addButton('submit', $this->locale->_('save'), 'btn btn-primary');
$this->assign('form', $f);
echo $this->includeTemplate('layout\form');