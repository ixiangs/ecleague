<?php
$this->assign('breadcrumb', array(
    $this->html->anchor($this->localize->_('catalogue_manage')),
    $this->html->anchor($this->localize->_($this->router->action == 'add' ? "catalogue_new_product" : "catalogue_edit_product"))
));

$this->assign('navigationBar', array(
    $this->html->anchor($this->localize->_('back'), $this->router->buildUrl('list'))
));

$this->assign('toolbar', array(
    $this->html->button('button', $this->localize->_('save'), 'btn btn-primary')->setAttribute('data-submit', 'form1')
));

$langId = $this->localize->getLanguageId();
$f = $this->html->groupedForm();
$f->beginGroup('base_info', $this->localize->_('base_info'));
$f->addInputField('text', $this->localize->_('name'), 'name', 'data[name]', $this->model->name)
    ->addValidateRule('required', true);
$f->addInputField('text', $this->localize->_('catalogue_sku'), 'sku', 'data[sku]', $this->model->getSku())
    ->addValidateRule('required', true);
$f->addSelectField(array('1' => $this->localize->_('yes'), '0' => $this->localize->_('no')),
    $this->localize->_('enable'), 'enabled', 'data[enabled]', $this->model->getEnabled());
$f->addTextareaField($this->localize->_('description'), 'description', 'data[description]', $this->model->description)
    ->addValidateRule('required', true);
$f->endGroup();

$clang = $this->localize->getLanguage();
$clangId = $clang['id'];
foreach($this->attributeSet->getGroups() as $attrGroup):
    $f->beginGroup($attrGroup->getCode(), $attrGroup->names[$clangId]);
    foreach($attrGroup->getAttributes() as $attr):
        $av = $this->model->getData($attr->getName());
        $field = $attr->toFormField();
        $field->getInput()->setAttribute('value', $av);
        $f->addField($field);
    endforeach;
    $f->endGroup();
endforeach;
$f->addHidden('id', 'id', $this->model->getId());
$f->addHidden('attribute_set_id', 'data[attribute_set_id]', 1);
$this->assign('form', $f);
echo $this->includeTemplate('layout\form');