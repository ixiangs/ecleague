<?php
$this->assign('breadcrumb', array(
    $this->html->anchor($this->localize->_('locale_manage')),
    $this->html->anchor($this->localize->_('locale_import_dictionary'))
));

$this->assign('navigationBar', array(
    $this->html->anchor($this->localize->_('back'), $this->router->buildUrl('list'))
));

$this->assign('toolbar', array(
    $this->html->button('button', $this->localize->_('upload'), 'btn btn-primary')->setAttribute('data-submit', 'form1')
));

$f = $this->html->form();
$f->addInputField('file', $this->localize->_('select_file'), 'upload', 'upload', null)
    ->addValidateRule('required', true);
$this->assign('form', $f);
echo $this->includeTemplate('layout\form');