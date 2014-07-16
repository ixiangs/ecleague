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
$this->assign('form', $f);


$f->addStaticField($this->localize->_('weiweb_menu_type'), $this->typeName);
$f->newField($this->localize->_('title'), true,
    $this->html->textbox('title', 'data[title]', $this->model->getTitle())
        ->addValidateRule('required', true));
$f->newField($this->localize->_('weiweb_icon'), false,
    $this->html->newElement('iframe',
        array('src'=>$this->router->buildUrl('icon', array('id'=>$this->model->getId())), 'class'=>'upload-frame')));
$f->newField($this->localize->_('enable'), true,
    $this->html->select('enabled', 'data[enabled]', $this->model->getStatus(), array(
        '1' => $this->localize->_('yes'),
        '0' => $this->localize->_('no')
    )));
$this->includeTemplate($this->formPath);
$f->newField($this->localize->_('weiweb_link'), true,
    $this->html->textbox('link', 'data[link]', $this->model->getLink())
        ->addValidateRule('required', true));
$f->addHidden('id', 'data[id]', $this->model->getId());
$f->addHidden('type_id', 'data[type_id]', $this->model->getTypeId());
echo $this->includeTemplate('layout\form');