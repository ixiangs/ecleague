<?php
$this->assign('breadcrumb', array(
    $this->html->anchor($this->locale->_('dass_manage')),
    $this->html->anchor($this->locale->_('dass_attribute_list'))
));

$this->assign('navigationBar', array(
    $this->html->anchor($this->locale->_('add'), $this->router->buildUrl('type'))
));

$dt = $this->html->table($this->models);
$dt->addIndexColumn('#', 'index', 'index');
$dt->addLabelColumn($this->locale->_('code'), '{code}', 'small', 'small text-center');
$dt->addLabelColumn($this->locale->_('name'), '{name}', 'middle', 'middle text-center');
$dt->addOptionColumn($this->locale->_('dass_data_type'), '{data_type}', array(
    \Core\Dass\Model\AttributeModel::DATA_TYPE_ARRAY=>$this->locale->_('dass_data_type_array'),
    \Core\Dass\Model\AttributeModel::DATA_TYPE_BOOLEAN=>$this->locale->_('dass_data_type_boolean'),
    \Core\Dass\Model\AttributeModel::DATA_TYPE_DATE=>$this->locale->_('dass_data_type_date'),
    \Core\Dass\Model\AttributeModel::DATA_TYPE_EMAIL=>$this->locale->_('dass_data_type_email'),
    \Core\Dass\Model\AttributeModel::DATA_TYPE_INTEGER=>$this->locale->_('dass_data_type_integer'),
    \Core\Dass\Model\AttributeModel::DATA_TYPE_NUMBER=>$this->locale->_('dass_data_type_number'),
    \Core\Dass\Model\AttributeModel::DATA_TYPE_STRING=>$this->locale->_('dass_data_type_string')
    ), 'middle', 'middle text-center');
$dt->addOptionColumn($this->locale->_('dass_input_type'), '{input_type}',  array(
    \Core\Dass\Model\AttributeModel::INPUT_TYPE_CHECKBOX_LIST=>$this->locale->_('dass_input_type_checkboxlist'),
    \Core\Dass\Model\AttributeModel::INPUT_TYPE_DATE_PICKER=>$this->locale->_('dass_input_type_datepicker'),
    \Core\Dass\Model\AttributeModel::INPUT_TYPE_DROPDOWN=>$this->locale->_('dass_input_type_dropdown'),
    \Core\Dass\Model\AttributeModel::INPUT_TYPE_LISTBOX=>$this->locale->_('dass_input_type_listbox'),
    \Core\Dass\Model\AttributeModel::INPUT_TYPE_RADIO_LIST=>$this->locale->_('dass_input_type_raidolist'),
    \Core\Dass\Model\AttributeModel::INPUT_TYPE_TEXTBOX=>$this->locale->_('dass_input_type_textbox'),
    \Core\Dass\Model\AttributeModel::INPUT_TYPE_TEXTAREA=>$this->locale->_('dass_input_type_textarea'),
), 'middle', 'middle text-center');
$dt->addLabelColumn($this->locale->_('dass_display_label'), '{display_label}', '', '');
$dt->addLabelColumn($this->locale->_('dass_form_label'), '{form_label}', '', '');
$dt->addBooleanColumn($this->locale->_('dass_indexable'), 'indexable', $this->locale->_('yes'), $this->locale->_('no').'</span>',
    'small', 'small text-center');
$dt->addBooleanColumn($this->locale->_('dass_required'), 'required', $this->locale->_('yes'), $this->locale->_('no').'</span>',
    'small', 'small text-center');
$dt->addBooleanColumn($this->locale->_('dass_indexable'), 'indexable', $this->locale->_('yes'), $this->locale->_('no').'</span>',
    'small', 'small text-center');
$dt->addBooleanColumn($this->locale->_('status'), 'enabled', $this->locale->_('enabled'), $this->locale->_('disabled').'</span>',
    'small', 'small text-center');
$dt->addLinkColumn('', $this->locale->_('edit'), urldecode($this->router->buildUrl('edit', array('id' => '{id}'))), 'small', 'small edit');
$this->assign('datatable', $dt);

$p = $this->html->pagination($this->total, PAGINATION_SIZE, PAGINATION_RANGE);
$this->assign('pagination', $p);

echo $this->includeTemplate('layout\list');