<?php
$this->assign('navigationBar', array(
    $this->html->anchor($this->localize->_('back'), $this->router->findHistory('list'))
));

$this->assign('toolbar', array(
    $this->html->button('button', $this->localize->_('save'), 'btn btn-primary')->setAttribute('data-submit', 'form1')
));

$f = \Ixiangs\Entities\Helper::buildHtmlForm($this->model);
//$f = $this->html->form()
//    ->setAttribute('action', $this->router->buildUrl('save', '*'));
//$f->newField($this->localize->_('name'), true,
//    $this->html->textbox('name', 'data[name]', $this->model->getName())
//        ->addValidateRule('required', true));
//$f->newField($this->localize->_('description'), true,
//    $this->html->textbox('description', 'data[description]', $this->model->getName()));
//$f->addHidden('id', 'data[id]', $this->model->getId());
$this->assign('form', $f);
echo $this->includeTemplate('layout\form');