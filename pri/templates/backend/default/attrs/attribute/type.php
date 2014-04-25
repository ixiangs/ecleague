<?php
$this->assign('breadcrumb', array(
    $this->html->anchor($this->locale->_('attrs_manage')),
    $this->html->anchor($this->locale->_('attrs_add_attribute'))
));

$this->assign('navigationBar', array(
    $this->html->anchor($this->locale->_('back'), $this->router->buildUrl('list'))
));

$this->assign('toolbar', array(
    $this->html->button('button', $this->locale->_('next_step'), 'btn btn-primary')->setAttribute('data-submit', 'form1')
));

$f = $this->html->form()->setAttribute(array(
    'action'=>$this->router->buildUrl('add'),
    'method'=>'get')
);
$f->addSelectField(array(
    \Core\Attrs\Model\AttributeModel::DATA_TYPE_STRING=>$this->locale->_('attrs_data_type_string'),
    \Core\Attrs\Model\AttributeModel::DATA_TYPE_INTEGER=>$this->locale->_('attrs_data_type_integer'),
    \Core\Attrs\Model\AttributeModel::DATA_TYPE_NUMBER=>$this->locale->_('attrs_data_type_number'),
    \Core\Attrs\Model\AttributeModel::DATA_TYPE_BOOLEAN=>$this->locale->_('attrs_data_type_boolean'),
    \Core\Attrs\Model\AttributeModel::DATA_TYPE_DATE=>$this->locale->_('attrs_data_type_date'),
    \Core\Attrs\Model\AttributeModel::DATA_TYPE_EMAIL=>$this->locale->_('attrs_data_type_email'),
    \Core\Attrs\Model\AttributeModel::DATA_TYPE_ARRAY=>$this->locale->_('attrs_data_type_array')),
    $this->locale->_('attrs_data_type'), 'data_type', 'data_type')
    ->addValidateRule('required', true)
    ->getInput()->setCaption('');
$f->addSelectField(array(
    \Core\Attrs\Model\AttributeModel::INPUT_TYPE_TEXTBOX=>$this->locale->_('attrs_input_type_textbox'),
    \Core\Attrs\Model\AttributeModel::INPUT_TYPE_TEXTAREA=>$this->locale->_('attrs_input_type_textarea'),
    \Core\Attrs\Model\AttributeModel::INPUT_TYPE_EDITOR=>$this->locale->_('attrs_input_type_editor'),
    \Core\Attrs\Model\AttributeModel::INPUT_TYPE_DROPDOWN=>$this->locale->_('attrs_input_type_dropdown'),
    \Core\Attrs\Model\AttributeModel::INPUT_TYPE_LISTBOX=>$this->locale->_('attrs_input_type_listbox'),
    \Core\Attrs\Model\AttributeModel::INPUT_TYPE_DATE_PICKER=>$this->locale->_('attrs_input_type_datepicker'),
    \Core\Attrs\Model\AttributeModel::INPUT_TYPE_CHECKBOX_LIST=>$this->locale->_('attrs_input_type_checkboxlist'),
    \Core\Attrs\Model\AttributeModel::INPUT_TYPE_RADIO_LIST=>$this->locale->_('attrs_input_type_raidolist')),
    $this->locale->_('attrs_input_type'), 'input_type', 'input_type')
    ->addValidateRule('required', true)
    ->getInput()->setCaption('');
$f->addHiddenField('set_id', 'set_id', $this->request->getQuery('set_id'));
$f->addHiddenField('component_id', 'component_id', $this->request->getQuery('component_id'));
$this->assign('form', $f);
echo $this->includeTemplate('layout\form');