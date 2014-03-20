<?php
$this->assign('breadcrumb', array(
    array('text'=>$this->locale->_('locale_manage')),
    array('text'=>$this->locale->_('locale_language_list'), 'url'=>$this->router->buildUrl('list')),
    array('text'=>$this->language->getName(), 'active'=>true)
));

$this->assign('buttons', array(
    array('text'=>$this->locale->_('back'), 'url'=>$this->router->buildUrl('list')),
    $this->html->button('button', $this->locale->_('new'), 'btn btn-success'),
    $this->html->button('button', $this->locale->_('save'), 'btn btn-primary')->addAttribute('data-submit', 'form1')
));

$f = $this->html->form();
$f->addInputField('text', $this->locale->_('code'), 'code', 'code', $this->model->getCode())
    ->addValidateRule('required', true);
$f->addInputField('text', $this->locale->_('text'), 'name', 'name', $this->model->getName())
    ->addValidateRule('required', true);
$this->assign('form', $f);
echo $this->includeTemplate('layout\form');