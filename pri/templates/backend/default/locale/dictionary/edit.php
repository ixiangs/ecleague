<?php
$this->assign('breadcrumb', array(
    $this->html->anchor($this->locale->_('locale_manage')),
    $this->html->anchor($this->language->getName()),
    $this->html->anchor($this->locale->_('locale_dictionary')),
    $this->html->anchor($this->locale->_('edit')),
));

$this->assign('navigationBar', array(
    $this->html->anchor($this->locale->_('back'), $this->router->buildUrl('list', array('languageid'=>$this->language->getId()))),
));

$this->assign('toolbar', array(
    $this->html->button('button', $this->locale->_('save'), 'btn btn-primary')->setAttribute('data-submit', 'form1')
));

$f = $this->html->form();
$len = count($this->models);

$f->addLabelField($this->locale->_('code'), $this->model->getCode());
$f->addInputField('text', $this->locale->_('text'), 'label', 'label', $this->model->getLabel())
    ->addValidateRule('required', true);
$f->addHiddenField('language_id', 'language_id', $this->model->getLanguageId());
$f->addHiddenField('id', 'id', $this->model->getId());
$this->assign('form', $f);
echo $this->includeTemplate('layout\form');