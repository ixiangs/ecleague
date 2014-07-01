<?php
$this->assign('navigationBar', array(
    $this->html->anchor($this->locale->_('entities_attribute_new'), $this->router->buildUrl('choose'))
));

$clang = $this->locale->getLanguage();
$dt = $this->html->grid($this->models);
$dt->addIndexColumn('#', 'index', 'index');
$dt->addLabelColumn($this->locale->_('name'), '{name}');
$dt->addLabelColumn($this->locale->_('source'), '{component_name}', 'large')
        ->setEmptyText($this->locale->_('entities_common'));
$dt->addOptionColumn($this->locale->_('entities_data_type'), '{data_type}', array(
    \Ixiangs\Entities\Constant::DATA_TYPE_ARRAY=>$this->locale->_('entities_data_type_array'),
    \Ixiangs\Entities\Constant::DATA_TYPE_BOOLEAN=>$this->locale->_('entities_data_type_boolean'),
    \Ixiangs\Entities\Constant::DATA_TYPE_DATE=>$this->locale->_('entities_data_type_date'),
    \Ixiangs\Entities\Constant::DATA_TYPE_EMAIL=>$this->locale->_('entities_data_type_email'),
    \Ixiangs\Entities\Constant::DATA_TYPE_INTEGER=>$this->locale->_('entities_data_type_integer'),
    \Ixiangs\Entities\Constant::DATA_TYPE_NUMBER=>$this->locale->_('entities_data_type_number'),
    \Ixiangs\Entities\Constant::DATA_TYPE_STRING=>$this->locale->_('entities_data_type_string')
    ), 'small', 'small text-center');
$dt->addOptionColumn($this->locale->_('entities_input_type'), '{input_type}',  array(
    \Ixiangs\Entities\Constant::INPUT_TYPE_OPTION_LIST=>$this->locale->_('entities_input_type_option_list'),
    \Ixiangs\Entities\Constant::INPUT_TYPE_DATE_PICKER=>$this->locale->_('entities_input_type_datepicker'),
    \Ixiangs\Entities\Constant::INPUT_TYPE_SELECT=>$this->locale->_('entities_input_type_select'),
    \Ixiangs\Entities\Constant::INPUT_TYPE_TEXTBOX=>$this->locale->_('entities_input_type_textbox'),
    \Ixiangs\Entities\Constant::INPUT_TYPE_TEXTAREA=>$this->locale->_('entities_input_type_textarea'),
    \Ixiangs\Entities\Constant::INPUT_TYPE_EDITOR=>$this->locale->_('entities_input_type_editor')
), 'small', 'small text-center');
$dt->addBooleanColumn($this->locale->_('status'), 'enabled', $this->locale->_('enabled'), $this->locale->_('disabled'),
    'small', 'small text-center');
$dt->addLinkColumn('', $this->locale->_('edit'), urldecode($this->router->buildUrl('edit', array('id' => '{id}'))), 'small', 'small edit');
$dt->addLinkButtonColumn('', $this->locale->_('delete'), "deleteConfirm('".urldecode($this->router->buildUrl('delete', array('id'=>'{id}')))."')", 'edit', 'edit');
$this->assign('datatable', $dt);

$p = $this->html->pagination($this->total, PAGINATION_SIZE, PAGINATION_RANGE);
$this->assign('pagination', $p);

echo $this->includeTemplate('layout\list');