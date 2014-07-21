<?php
$toolbarArr = array(
    $this->html->anchor($this->localize->_('back'), $this->router->buildUrl('list'))
        ->setAttribute('class', 'btn btn-default'),
    $this->html->button('button', $this->localize->_('save'), 'btn btn-primary')->setAttribute('data-submit', 'form1')
);
if($this->model->repairer_id):
    unset($toolbarArr[1]);
endif;
$this->assign('toolbar', $toolbarArr);

$f = $this->html->form();
$f->addStaticField($this->localize->_('realty_owner'), $this->model->getContacts());
$f->addStaticField($this->localize->_('phone'), $this->model->getPhone());
$f->addStaticField($this->localize->_('realty_room_number'),
    $this->model->getBuilding().'/'.$this->model->getFloor().'/'.$this->model->getRoom());
$f->addStaticField($this->localize->_('realty_repair_time'), $this->model->getCreatedTime());
$f->addStaticField($this->localize->_('realty_repair_content'), $this->model->getContent());
if(!$this->model->repairer_id):
    $f->newField($this->localize->_('realty_repairer'), true,
        $this->html->select('repairer_id', 'repairer_id', $this->model->getRepairId(), $this->repairers)
            ->setCaption('')
            ->addValidateRule('required', 'true'));
else:
    $f->addStaticField($this->localize->_('realty_repairer'), $this->model->getRepairerName());
    $f->addStaticField($this->localize->_('realty_repair_time'), $this->model->getRepairTime());
endif;
$this->assign('form', $f);
echo $this->includeTemplate('layout\form');