<?php
$this->assign('navigationBar', array(
    $this->html->anchor($this->locale->_('attrs_field_new'), $this->router->buildUrl('add'))
));

$clang = $this->locale->getLanguage();
$dt = $this->html->grid($this->models);
$dt->addIndexColumn('#', 'index', 'index');
$dt->addLabelColumn($this->locale->_('name'), '{name}', 'large', 'left');
$dt->addOptionColumn($this->locale->_('attrs_data_type'), '{data_type}', array(
    \Ixiangs\Attrs\Constant::DATA_TYPE_ARRAY=>$this->locale->_('attrs_data_type_array'),
    \Ixiangs\Attrs\Constant::DATA_TYPE_BOOLEAN=>$this->locale->_('attrs_data_type_boolean'),
    \Ixiangs\Attrs\Constant::DATA_TYPE_DATE=>$this->locale->_('attrs_data_type_date'),
    \Ixiangs\Attrs\Constant::DATA_TYPE_EMAIL=>$this->locale->_('attrs_data_type_email'),
    \Ixiangs\Attrs\Constant::DATA_TYPE_INTEGER=>$this->locale->_('attrs_data_type_integer'),
    \Ixiangs\Attrs\Constant::DATA_TYPE_NUMBER=>$this->locale->_('attrs_data_type_number'),
    \Ixiangs\Attrs\Constant::DATA_TYPE_STRING=>$this->locale->_('attrs_data_type_string')
    ), 'small', 'small text-center');
$dt->addOptionColumn($this->locale->_('attrs_input_type'), '{input_type}',  array(
    \Ixiangs\Attrs\Constant::INPUT_TYPE_OPTION_LIST=>$this->locale->_('attrs_input_type_option_list'),
    \Ixiangs\Attrs\Constant::INPUT_TYPE_DATE_PICKER=>$this->locale->_('attrs_input_type_datepicker'),
    \Ixiangs\Attrs\Constant::INPUT_TYPE_SELECT=>$this->locale->_('attrs_input_type_select'),
    \Ixiangs\Attrs\Constant::INPUT_TYPE_TEXTBOX=>$this->locale->_('attrs_input_type_textbox'),
    \Ixiangs\Attrs\Constant::INPUT_TYPE_TEXTAREA=>$this->locale->_('attrs_input_type_textarea'),
    \Ixiangs\Attrs\Constant::INPUT_TYPE_EDITOR=>$this->locale->_('attrs_input_type_editor')
), 'small', 'small text-center');
$dt->addBooleanColumn($this->locale->_('attrs_indexable'), 'indexable', $this->locale->_('yes'), $this->locale->_('no'),
    'small', 'small text-center');
$dt->addBooleanColumn($this->locale->_('attrs_required'), 'required', $this->locale->_('yes'), $this->locale->_('no'),
    'small', 'small text-center');
$dt->addBooleanColumn($this->locale->_('status'), 'enabled', $this->locale->_('enabled'), $this->locale->_('disabled'),
    'small', 'small text-center');
$dt->addLinkColumn('', $this->locale->_('edit'), urldecode($this->router->buildUrl('edit', array('id' => '{id}'))), 'small', 'small edit');

$dt->addLinkButtonColumn('', $this->locale->_('delete'), "deleteConfirm('".urldecode($this->router->buildUrl('delete', array('id'=>'{id}')))."')", 'edit', 'edit');
$this->assign('datatable', $dt);

echo $this->includeTemplate('layout\list');