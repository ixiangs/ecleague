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
$f->newField($this->localize->_('realty_floor_count'), true,
    $this->html->textbox('floor_count', 'data[floor_count]', $this->model->getFloorCount())
        ->addValidateRule('required', true)
        ->addValidateRule('integer', true));
$f->newField($this->localize->_('realty_room_count'), true,
    $this->html->textbox('room_count', 'data[room_count]', $this->model->getRoomCount())
        ->addValidateRule('required', true)
        ->addValidateRule('integer', true));
$f->addHidden('id', 'data[id]', $this->model->getId());
$this->assign('form', $f);
echo $this->includeTemplate('layout\form');