<?php
$this->assign('breadcrumb', array(
    array('text'=>$this->locale->_('locale_manage')),
    array('text'=>$this->locale->_('locale_import_dictionary'), 'url'=>$this->router->buildUrl('list'))
));

$this->assign('buttons', array(
    array('text'=>$this->locale->_('back'), 'url'=>$this->router->buildUrl('list')),
    $this->html->button('button', $this->locale->_('upload'), 'btn btn-primary')->setAttribute('data-submit', 'form1')
));

$f = $this->html->form();
$f->addInputField('file', $this->locale->_('select_file'), 'upload', 'upload', null)
    ->addValidateRule('required', true);
$this->assign('form', $f);
echo $this->includeTemplate('layout\form');