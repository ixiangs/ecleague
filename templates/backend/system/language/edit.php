<?php
$this->assign('navigationBar', array(
    $this->html->anchor($this->localize->_('back'), $this->router->buildUrl('list'))
));

$this->assign('toolbar', array(
    $this->html->button('button', $this->localize->_('save'), 'btn btn-primary')->setAttribute('data-submit', 'form1')
));

$f = $this->html->form()
    ->setAttribute('action', $this->router->buildUrl('save', '*'));
if ($this->model->getId()):
    $f->addStaticField($this->localize->_('code'), $this->model->getCode());
else:
    $f->newField($this->localize->_('code'), true,
        $this->html->textbox('code', 'data[code]', $this->model->getCode())
            ->addValidateRule('required', true));
endif;
$f->newField($this->localize->_('name'), true,
    $this->html->textbox('name', 'data[name]', $this->model->getName())
        ->addValidateRule('required', true));
$f->newField($this->localize->_('locale_timezone'), true,
    $this->html->textbox('timezone', 'data[timezone]', $this->model->getTimezone())
        ->addValidateRule('required', true));
$f->newField($this->localize->_('locale_currency_code'), true,
    $this->html->textbox('currency_code', 'data[currency_code]', $this->model->getCurrencyCode())
        ->addValidateRule('required', true));
$f->newField($this->localize->_('locale_currency_symbol'), true,
    $this->html->textbox('currency_symbol', 'data[currency_symbol]', $this->model->getCurrencySymbol())
        ->addValidateRule('required', true));
$f->newField($this->localize->_('locale_short_date_format'), true,
    $this->html->textbox('short_date_format', 'data[short_date_format]', $this->model->getShortDateFormat())
        ->addValidateRule('required', true));
$f->newField($this->localize->_('locale_long_date_format'), true,
    $this->html->textbox('long_date_format', 'data[long_date_format]', $this->model->getLongDateFormat())
        ->addValidateRule('required', true));
$f->newField($this->localize->_('enable'), true,
    $this->html->select('enabled', 'data[enabled]', $this->model->getEnabled(),
        array('1' => $this->localize->_('yes'), '0' => $this->localize->_('no'))));
$f->addHidden('id', 'data[id]', $this->model->getId());
$this->assign('form', $f);
echo $this->includeTemplate('layout\form');