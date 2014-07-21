<?php
$toolbarArr = array(
    $this->html->anchor($this->localize->_('back'), $this->router->buildUrl('list'))
        ->setAttribute('class', 'btn btn-default'),
    $this->html->button('button', $this->localize->_('delete'), 'btn btn-danger')
        ->setEvent('click', "deleteConfirm('".$this->router->buildUrl('delete', array('id'=>$this->model->getId()))."')"),
    $this->html->button('button', $this->localize->_('save'), 'btn btn-primary')->setAttribute('data-submit', 'form1')
);
if($this->router->action == 'add'):
    unset($toolbarArr[1]);
endif;

$this->assign('toolbar', $toolbarArr);

$f = $this->html->form();
$f->newField($this->localize->_('name'), true,
    $this->html->textbox('name', 'data[name]', $this->model->getName())
        ->addValidateRule('required', true));
$f->newField($this->localize->_('mobile'), true,
    $this->html->textbox('mobile', 'data[mobile]', $this->model->getMobile()));
$f->newField($this->localize->_('phone'), true,
    $this->html->textbox('phone', 'data[phone]', $this->model->getPhone()));
$f->newField($this->localize->_('realty_staff_position'), true,
    $this->html->textbox('position', 'data[position]', $this->model->getPhone()));
$f->addHidden('id', 'data[id]', $this->model->getId());
$this->assign('form', $f);
echo $this->includeTemplate('layout\form');