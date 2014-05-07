<?php
$this->assign('breadcrumb', array(
    $this->html->anchor($this->locale->_('locale_manage')),
    $this->html->anchor($this->language->getName()),
    $this->html->anchor($this->locale->_('locale_edit_dictionary')),
));

$this->assign('navigationBar', array(
    $this->html->anchor($this->locale->_('back'), $this->router->buildUrl('list', array('languageid'=>$this->language->getId()))),
));

$this->assign('toolbar', array(
    $this->html->button('button', $this->locale->_('save'), 'btn btn-primary')->setAttribute('data-submit', 'form1')
));

$f = $this->html->form();
$len = count($this->models);
$f->newField($this->locale->_('code'), true,
    $this->html->textbox('code', 'data[code]', $this->model->getCode())
        ->addValidateRule('required', true));
$f->newField($this->locale->_('text'), true,
    $this->html->textbox('label', 'data[label]', $this->model->getLabel()))
    ->addValidateRule('required', true);
$f->addHidden('language_id', 'data[language_id]', $this->model->getLanguageId());
$f->addHidden('id', 'data[id]', $this->model->getId());
$this->assign('form', $f);
echo $this->includeTemplate('layout\form');