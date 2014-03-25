<?php
$this->assign('breadcrumb', array(
    array('text'=>$this->locale->_('locale_manage')),
    array('text'=>$this->locale->_('locale_language_list'), 'url'=>$this->router->buildUrl('list')),
    array('text'=>$this->locale->_($this->router->action == 'add' ? "add" : "edit"), 'active'=>true)
));

$this->assign('buttons', array(
    array('text'=>$this->locale->_('back'), 'url'=>$this->router->buildUrl('list')),
    $this->html->button('button', $this->locale->_('save'), 'btn btn-primary')->setAttribute('data-submit', 'form1')
));

$f = $this->html->form();
$f->addInputField('text', $this->locale->_('code'), 'code', 'code', $this->model->getCode())
    ->addValidateRule('required', true);
$f->addInputField('text', $this->locale->_('name'), 'name', 'name', $this->model->getName())
    ->addValidateRule('required', true);
$f->addInputField('text', $this->locale->_('locale_timezone'), 'timezone', 'timezone', $this->model->getTimezone())
    ->addValidateRule('required', true);
$f->addInputField('text', $this->locale->_('locale_currency_code'), 'currency_code', 'currency_code', $this->model->getCurrencyCode())
    ->addValidateRule('required', true);
$f->addInputField('text', $this->locale->_('locale_currency_symbol'), 'currency_symbol', 'currency_symbol', $this->model->getCurrencySymbol())
    ->addValidateRule('required', true);
$f->addInputField('text', $this->locale->_('locale_short_date_format'), 'short_date_format', 'short_date_format', $this->model->getShortDateFormat())
    ->addValidateRule('required', true);
$f->addInputField('text', $this->locale->_('locale_long_date_format'), 'long_date_format', 'long_date_format', $this->model->getLongDateFormat())
    ->addValidateRule('required', true);
$f->addSelectField(array('1'=>$this->locale->_('yes'), '0'=>$this->locale->_('no')),
    $this->locale->_('enable'), 'enabled', 'enabled', $this->model->getEnabled());
$this->assign('form', $f);
echo $this->includeTemplate('layout\form');