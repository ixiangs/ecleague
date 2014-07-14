<?php
$toolbarArr = array(
    $this->html->anchor($this->localize->_('back'), $this->router->buildUrl('list'))
        ->setAttribute('class', 'btn btn-default'),
);

$this->assign('toolbar', $toolbarArr);

$f = $this->html->form();
$f->addStaticField($this->localize->_('realty_owner'), $this->model->getContacts());
$f->addStaticField($this->localize->_('phone'), $this->model->getPhone());
$f->addStaticField($this->localize->_('realty_room_number'),
    $this->model->getBuilding().'/'.$this->model->getFloor().'/'.$this->model->getRoom());
$f->addStaticField($this->localize->_('realty_complaint_time'), $this->model->getCreatedTime());
$f->addStaticField($this->localize->_('realty_complaint_content'), $this->model->getContent());
$this->assign('form', $f);
echo $this->includeTemplate('layout\form');