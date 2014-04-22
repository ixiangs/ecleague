<?php
$this->assign('breadcrumb', array(
    $this->html->anchor($this->locale->_('attrs_manage')),
    $this->html->anchor($this->locale->_('attrs_attribute_list'))
));

$this->assign('navigationBar', array(
    $this->html->anchor($this->locale->_('add'), $this->router->buildUrl('type'))
));

$clang = $this->locale->getCurrentLanguage();
$dt = $this->html->grid($this->models);
$dt->addIndexColumn('#', 'index', 'index');
$dt->addLabelColumn($this->locale->_('name'), '{name}', 'middle', 'left');
$dt->addOptionColumn($this->locale->_('attrs_data_type'), '{data_type}', array(
    \Core\Attrs\Model\AttributeModel::DATA_TYPE_ARRAY=>$this->locale->_('attrs_data_type_array'),
    \Core\Attrs\Model\AttributeModel::DATA_TYPE_BOOLEAN=>$this->locale->_('attrs_data_type_boolean'),
    \Core\Attrs\Model\AttributeModel::DATA_TYPE_DATE=>$this->locale->_('attrs_data_type_date'),
    \Core\Attrs\Model\AttributeModel::DATA_TYPE_EMAIL=>$this->locale->_('attrs_data_type_email'),
    \Core\Attrs\Model\AttributeModel::DATA_TYPE_INTEGER=>$this->locale->_('attrs_data_type_integer'),
    \Core\Attrs\Model\AttributeModel::DATA_TYPE_NUMBER=>$this->locale->_('attrs_data_type_number'),
    \Core\Attrs\Model\AttributeModel::DATA_TYPE_STRING=>$this->locale->_('attrs_data_type_string')
    ), 'small', 'small text-center');
$dt->addOptionColumn($this->locale->_('attrs_input_type'), '{input_type}',  array(
    \Core\Attrs\Model\AttributeModel::INPUT_TYPE_CHECKBOX_LIST=>$this->locale->_('attrs_input_type_checkboxlist'),
    \Core\Attrs\Model\AttributeModel::INPUT_TYPE_DATE_PICKER=>$this->locale->_('attrs_input_type_datepicker'),
    \Core\Attrs\Model\AttributeModel::INPUT_TYPE_DROPDOWN=>$this->locale->_('attrs_input_type_dropdown'),
    \Core\Attrs\Model\AttributeModel::INPUT_TYPE_LISTBOX=>$this->locale->_('attrs_input_type_listbox'),
    \Core\Attrs\Model\AttributeModel::INPUT_TYPE_RADIO_LIST=>$this->locale->_('attrs_input_type_raidolist'),
    \Core\Attrs\Model\AttributeModel::INPUT_TYPE_TEXTBOX=>$this->locale->_('attrs_input_type_textbox'),
    \Core\Attrs\Model\AttributeModel::INPUT_TYPE_TEXTAREA=>$this->locale->_('attrs_input_type_textarea'),
), 'small', 'small text-center');
$dt->addBooleanColumn($this->locale->_('attrs_indexable'), 'indexable', $this->locale->_('yes'), $this->locale->_('no'),
    'small', 'small text-center');
$dt->addBooleanColumn($this->locale->_('attrs_required'), 'required', $this->locale->_('yes'), $this->locale->_('no'),
    'small', 'small text-center');
$dt->addBooleanColumn($this->locale->_('status'), 'enabled', $this->locale->_('enabled'), $this->locale->_('disabled'),
    'small', 'small text-center');
$dt->addLabelColumn($this->locale->_('memo'), '{memo}', '', 'left')
    ->setCellRenderer(function($col, $row) use($clang){
        $col->getCell()->getChild(0)
            ->removeBindableAttribute('text')
            ->setAttribute('text', $row->memo[$clang['id']]);
        return $col->getCell()->renderBegin().$col->getCell()->renderInner().$col->getCell()->renderEnd();
    });
$dt->addLinkColumn('', $this->locale->_('edit'), urldecode($this->router->buildUrl('edit', array('id' => '{id}'))), 'small', 'small edit');

$dt->addLinkColumn('', $this->locale->_('attrs_option'),
                    urldecode($this->router->buildUrl('options', array('attributeid' => '{id}'))), 'small', 'small edit')
    ->setCellRenderer(function($col, $row){
        switch($row['input_type']){
            case \Core\Attrs\Model\AttributeModel::INPUT_TYPE_DROPDOWN:
            case \Core\Attrs\Model\AttributeModel::INPUT_TYPE_LISTBOX:
            case \Core\Attrs\Model\AttributeModel::INPUT_TYPE_CHECKBOX_LIST:
            case \Core\Attrs\Model\AttributeModel::INPUT_TYPE_RADIO_LIST:
                $col->getCell()->getChild(0)->bindAttribute($row);
                return $col->getCell()->renderBegin().$col->getCell()->renderInner().$col->getCell()->renderEnd();
            default:
                return $col->getCell()->renderBegin().$col->getCell()->renderEnd();
        }
    });
$this->assign('datatable', $dt);

$p = $this->html->pagination($this->total, PAGINATION_SIZE, PAGINATION_RANGE);
$this->assign('pagination', $p);

echo $this->includeTemplate('layout\list');