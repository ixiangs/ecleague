<?php
$this->assign('navigationBar', array(
    $this->html->anchor($this->localize->_('back'), $this->router->findHistory('entity/list')),
    $this->html->anchor($this->localize->_('entities_field_new'), $this->router->buildUrl('add', '*'))
));

$clang = $this->localize->getLanguage();
$dt = $this->html->grid($this->models);
$dt->addIndexColumn('#', 'index', 'index');
$dt->addLabelColumn($this->localize->_('name'), '{name}', '', 'left');
$dt->addOptionColumn($this->localize->_('entities_data_type'), '{data_type}', array(
    \Ixiangs\Entities\Constant::DATA_TYPE_ARRAY=>$this->localize->_('entities_data_type_array'),
    \Ixiangs\Entities\Constant::DATA_TYPE_BOOLEAN=>$this->localize->_('entities_data_type_boolean'),
    \Ixiangs\Entities\Constant::DATA_TYPE_DATE=>$this->localize->_('entities_data_type_date'),
    \Ixiangs\Entities\Constant::DATA_TYPE_EMAIL=>$this->localize->_('entities_data_type_email'),
    \Ixiangs\Entities\Constant::DATA_TYPE_INTEGER=>$this->localize->_('entities_data_type_integer'),
    \Ixiangs\Entities\Constant::DATA_TYPE_NUMBER=>$this->localize->_('entities_data_type_number'),
    \Ixiangs\Entities\Constant::DATA_TYPE_STRING=>$this->localize->_('entities_data_type_string')
    ), 'middle', 'text-center');
$dt->addOptionColumn($this->localize->_('entities_input_type'), '{input_type}',  array(
    \Ixiangs\Entities\Constant::INPUT_TYPE_OPTION_LIST=>$this->localize->_('entities_input_type_option_list'),
    \Ixiangs\Entities\Constant::INPUT_TYPE_DATE_PICKER=>$this->localize->_('entities_input_type_datepicker'),
    \Ixiangs\Entities\Constant::INPUT_TYPE_SELECT=>$this->localize->_('entities_input_type_select'),
    \Ixiangs\Entities\Constant::INPUT_TYPE_TEXTBOX=>$this->localize->_('entities_input_type_textbox'),
    \Ixiangs\Entities\Constant::INPUT_TYPE_TEXTAREA=>$this->localize->_('entities_input_type_textarea'),
    \Ixiangs\Entities\Constant::INPUT_TYPE_EDITOR=>$this->localize->_('entities_input_type_editor')
), 'middle', 'text-center');
$dt->addBooleanColumn($this->localize->_('entities_indexable'), 'indexable', $this->localize->_('yes'), $this->localize->_('no'),
    'small', 'text-center');
$dt->addBooleanColumn($this->localize->_('entities_required'), 'required', $this->localize->_('yes'), $this->localize->_('no'),
    'small', 'text-center');
$dt->addLinkColumn('', $this->localize->_('edit'), urldecode($this->router->buildUrl('edit', array('id' => '{id}'))), 'edit', 'edit');

$dt->addLinkButtonColumn('', $this->localize->_('delete'), "deleteConfirm('".urldecode($this->router->buildUrl('delete', array('id'=>'{id}')))."')", 'edit', 'edit');
$this->assign('datatable', $dt);

echo $this->includeTemplate('layout\list');