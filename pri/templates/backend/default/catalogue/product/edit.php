<?php
$this->assign('breadcrumb', array(
    $this->html->anchor($this->locale->_('catalogue_manage')),
    $this->html->anchor($this->locale->_($this->router->action == 'add' ? "catalogue_new_product" : "catalogue_edit_product"))
));

$this->assign('navigationBar', array(
    $this->html->anchor($this->locale->_('back'), $this->router->buildUrl('list'))
));

$this->assign('toolbar', array(
    $this->html->button('button', $this->locale->_('save'), 'btn btn-primary')->setAttribute('data-submit', 'form1')
));

$langId = $this->locale->getCurrentLanguageId();
$f = $this->html->groupedForm();
$f->beginGroup('base_info', $this->locale->_('base_info'));
$f->addInputField('text', $this->locale->_('name'), 'name', 'data[name]', $this->model->name[$langId])
    ->addValidateRule('required', true);
$f->addInputField('text', $this->locale->_('catalogue_sku'), 'sku', 'data[sku]', $this->model->getSku())
    ->addValidateRule('required', true);
$f->addSelectField(array('1' => $this->locale->_('yes'), '0' => $this->locale->_('no')),
    $this->locale->_('enable'), 'enabled', 'data[enabled]', $this->model->getEnabled());
$f->addTextareaField($this->locale->_('description'), 'description', 'data[description]', $this->model->getSku())
    ->addValidateRule('required', true);
$f->endGroup();

$clang = $this->locale->getCurrentLanguage();
$clangId = $clang['id'];
foreach($this->attributeSet->getGroups() as $attrGroup):
    $f->beginGroup($attrGroup->getCode(), $attrGroup->names[$clangId]);
    foreach($attrGroup->getAttributes() as $attr):
        $av = $this->model->extension_data[$attr->getName()];
        $field = $attr->toFormField();
        $field->getInput()->setAttribute('value', $av);
        $f->addField($field);
    endforeach;
    $f->endGroup();
endforeach;
$f->addHiddenField('id', 'id', $this->model->getId());
$this->assign('form', $f);
echo $this->includeTemplate('layout\form');