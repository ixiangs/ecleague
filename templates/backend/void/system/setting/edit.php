<?php
$this->assign('toolbar', array(
    $this->html->button('button', $this->localize->_('save'), 'btn btn-primary')
        ->setAttribute('data-submit', 'form1')
));

$f = $this->html->form();
$f->newField($this->localize->_('website_title'), true,
    $this->html->textbox('website_title', 'data[website_title]', $this->model->getWebsiteTitle())
        ->addValidateRule('required', true));
$f->newField($this->localize->_('website_description'), true,
    $this->html->textarea('website_description', 'data[website_description]', $this->model->getWebsiteDescription()))
    ->addValidateRule('required', true);
$f->newField($this->localize->_('offline'), true,
    $this->html->select('offline', 'data[offline]', $this->model->getOffline(), array(
        '0' => $this->localize->_('no'),
        '1' => $this->localize->_('yes')
    )));
$this->assign('form', $f);
echo $this->includeTemplate('layout\form');