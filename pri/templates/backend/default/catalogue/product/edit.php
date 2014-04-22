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

$f = $this->html->groupedForm();
$f->beginGroup('base_info', $this->locale->_('base_info'));
$f->addInputField('text', $this->locale->_('name'), 'name', 'data[name]', $this->model->getName())
    ->addValidateRule('required', true);
$f->addInputField('text', $this->locale->_('catalogue_sku'), 'sku', 'data[sku]', $this->model->getSku())
    ->addValidateRule('required', true);
$f->addTextareaField($this->locale->_('description'), 'description', 'data[description]', $this->model->getSku())
    ->addValidateRule('required', true);
$f->addSelectField(array('1' => $this->locale->_('yes'), '0' => $this->locale->_('no')),
    $this->locale->_('enable'), 'enabled', 'data[enabled]', $this->model->getEnabled());
$f->endGroup();

$clang = $this->locale->getCurrentLanguage();
$clangId = $clang['id'];
foreach($this->attributeSet->getGroups() as $attrGroup):
    $f->beginGroup($attrGroup->getCode(), $attrGroup->names[$clangId]);
    foreach($attrGroup->getAttributes() as $attr):
        switch($attr->getInputType()):
            case \Core\Attrs\Model\AttributeModel::INPUT_TYPE_TEXTBOX:
                $field = $f->addInputField('text', $attr->display_text[$clangId], $attr->getName(), 'data['.$attr->getName().']');
                break;
            case \Core\Attrs\Model\AttributeModel::INPUT_TYPE_DROPDOWN:
                $options = \Toy\Util\ArrayUtil::toArray($attr->getOptions(), function($item, $index) use($clangId){
                    return array($item['labels'][$clangId], $item['value']);
                });
                $field = $f->addSelectField($options, $attr->display_text[$clangId], $attr->getName(), 'data['.$attr->getName().']');
                $field->getInput()->setCaption('');
                break;
        endswitch;
        if($attr->getRequired()):
            $field->addValidateRule('required', true);
        endif;
    endforeach;
    $f->endGroup();
endforeach;
$f->addHiddenField('id', 'id', $this->model->getId());
$this->assign('form', $f);
echo $this->includeTemplate('layout\form');