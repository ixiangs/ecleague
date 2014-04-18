<?php
$this->assign('breadcrumb', array(
    $this->html->anchor($this->locale->_('attrs_manage')),
    $this->html->anchor($this->locale->_('attrs_attribute_group'))
));

$this->assign('navigationBar', array(
    $this->html->anchor($this->locale->_('back'), $this->router->buildUrl('list'))
));

$this->assign('toolbar', array(
    $this->html->button('button', $this->locale->_('save'), 'btn btn-primary')->setAttribute('data-submit', 'form1')
));

$f = $this->html->groupedForm();
$f->beginGroup('tab_base', $this->locale->_('base_info'));
$f->addInputField('text', $this->locale->_('code'), 'code', 'data[code]', $this->model->getCode())
    ->addValidateRule('required', true);
$f->addSelectField(array('1'=>$this->locale->_('yes'), '0'=>$this->locale->_('no')),
    $this->locale->_('enable'), 'enabled', 'data[enabled]', $this->model->getEnabled());
$f->addCheckboxListField($this->attributes, $this->locale->_('attrs_attribute'), '', 'data[attribute_ids][]', $this->model->getAttributeIds())
    ->addValidateRule('required', true);
$f->endGroup();

foreach($this->locale->getLanguages() as $lang):
    $f->beginGroup('tab_lang_'.$lang['code'], $lang['name']);
    $f->addInputField('text', $this->locale->_('name'), 'name_'.$lang['id'], 'data[names]['.$lang['id'].']', $this->model->getCode())
        ->addValidateRule('required', true);
    $f->endGroup();
endforeach;

$f->addHiddenField('id', 'id', $this->model->getId());
$this->assign('form', $f);
echo $this->includeTemplate('layout\form');