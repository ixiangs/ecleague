<?php
$this->assign('breadcrumb', array(
    $this->html->anchor($this->locale->_('attrs_manage')),
    $this->html->anchor($this->locale->_('attrs_add_attribute'))
));

$this->assign('navigationBar', array(
    $this->html->anchor($this->locale->_('back'), $this->router->buildUrl('list'))
));

$this->assign('toolbar', array(
    $this->html->button('button', $this->locale->_('save'), 'btn btn-primary')->setAttribute('data-submit', 'form1')
));

$f = $this->html->form()->setAttribute();
$dataTypes = array(
    \Core\Attrs\Model\AttributeModel::DATA_TYPE_STRING=>$this->locale->_('attrs_data_type_string'),
    \Core\Attrs\Model\AttributeModel::DATA_TYPE_INTEGER=>$this->locale->_('attrs_data_type_integer'),
    \Core\Attrs\Model\AttributeModel::DATA_TYPE_NUMBER=>$this->locale->_('attrs_data_type_number'),
    \Core\Attrs\Model\AttributeModel::DATA_TYPE_BOOLEAN=>$this->locale->_('attrs_data_type_boolean'),
    \Core\Attrs\Model\AttributeModel::DATA_TYPE_DATE=>$this->locale->_('attrs_data_type_date'),
    \Core\Attrs\Model\AttributeModel::DATA_TYPE_EMAIL=>$this->locale->_('attrs_data_type_email'),
    \Core\Attrs\Model\AttributeModel::DATA_TYPE_ARRAY=>$this->locale->_('attrs_data_type_array')
);
$inputTypes = array(
    \Core\Attrs\Model\AttributeModel::INPUT_TYPE_TEXTBOX=>$this->locale->_('attrs_input_type_textbox'),
    \Core\Attrs\Model\AttributeModel::INPUT_TYPE_TEXTAREA=>$this->locale->_('attrs_input_type_textarea'),
    \Core\Attrs\Model\AttributeModel::INPUT_TYPE_DROPDOWN=>$this->locale->_('attrs_input_type_dropdown'),
    \Core\Attrs\Model\AttributeModel::INPUT_TYPE_LISTBOX=>$this->locale->_('attrs_input_type_listbox'),
    \Core\Attrs\Model\AttributeModel::INPUT_TYPE_DATE_PICKER=>$this->locale->_('attrs_input_type_datepicker'),
    \Core\Attrs\Model\AttributeModel::INPUT_TYPE_CHECKBOX_LIST=>$this->locale->_('attrs_input_type_checkboxlist'),
    \Core\Attrs\Model\AttributeModel::INPUT_TYPE_RADIO_LIST=>$this->locale->_('attrs_input_type_raidolist')
);
$f = $this->html->groupedForm();

$f->beginGroup('tab_base', $this->locale->_('base_info'));
$f->addLabelField($this->locale->_('attrs_data_type'), $dataTypes[$this->model->getDataType()]);
$f->addLabelField($this->locale->_('attrs_input_type'), $inputTypes[$this->model->getInputType()]);
$f->addInputField('text', $this->locale->_('code'), 'code', 'data[code]', $this->model->getCode())
    ->addValidateRule('required', true);
$f->addSelectField(array('1'=>$this->locale->_('yes'), '0'=>$this->locale->_('no')),
    $this->locale->_('attrs_indexable'), 'indexable', 'data[indexable]', $this->model->getEnabled());
$f->addSelectField(array('1'=>$this->locale->_('yes'), '0'=>$this->locale->_('no')),
    $this->locale->_('enable'), 'enabled', 'data[enabled]', $this->model->getEnabled());
$f->endGroup();

$f->beginGroup('tab_input', $this->locale->_('attrs_input_setting'));
$f->addSelectField(array('1'=>$this->locale->_('yes'), '0'=>$this->locale->_('no')),
    $this->locale->_('attrs_required'), 'required', 'data[required]', $this->model->getEnabled());
$f->addInputField('text', $this->locale->_('attrs_input_id'), 'input_id', 'data[input_id]', $this->model->getInputId());
$f->addInputField('text', $this->locale->_('attrs_input_name'), 'input_name', 'data[input_name]', $this->model->getInputName());
$vs = $this->model->getInputSetting();
switch($this->model->getInputType()):
    case \Core\Attrs\Model\AttributeModel::INPUT_TYPE_TEXTBOX:
        switch($this->model->getDataType()):
            case \Core\Attrs\Model\AttributeModel::DATA_TYPE_INTEGER:
                $f->addInputField('text', $this->locale->_('attrs_min_value'), 'min_value', 'data[input_setting][min_value]', $vs['min_value'])
                    ->addValidateRule('integer', true);
                $f->addInputField('text', $this->locale->_('attrs_max_value'), 'max_value', 'data[input_setting][max_value]', $vs['max_value'])
                    ->addValidateRule('integer', true)->addValidateRule('greatto', '#min_value', $this->locale->_('attrs_max_great_min'));
            break;
            case \Core\Attrs\Model\AttributeModel::DATA_TYPE_NUMBER:
                $f->addInputField('text', $this->locale->_('attrs_min_value'), 'min_value', 'data[input_setting][min_value]', $vs['min_value'])
                    ->addValidateRule('number', true);
                $f->addInputField('text', $this->locale->_('attrs_max_value'), 'max_value', 'data[input_setting][max_value]', $vs['max_value'])
                    ->addValidateRule('number', true)->addValidateRule('greatto', '#min_value', $this->locale->_('attrs_max_great_min'));
                break;
            case \Core\Attrs\Model\AttributeModel::DATA_TYPE_STRING:
                $f->addInputField('text', $this->locale->_('attrs_max_length'), 'max_length', 'data[input_setting][max_length]', $vs['max_length'])
                    ->addValidateRule('integer', true);
            break;
        endswitch;
        break;
endswitch;
$f->endGroup();

$names = $this->model->getNames(array());
$dlabels = $this->model->getDisplayLabels(array());
$flabels = $this->model->getFormLabels(array());
foreach($this->locale->getLanguages() as $lang):

    $f->beginGroup('tab_lang_'.$lang['code'], $lang['name']);
    $f->addInputField('text', $this->locale->_('name'), 'name_'.$lang['id'], 'data[names]['.$lang['id'].']',
        array_key_exists($lang['id'], $names)? $names[$lang['id']]: '')
        ->addValidateRule('required', true);
    $f->addInputField('text', $this->locale->_('attrs_display_label'), 'display_label_'.$lang['id'], 'data[display_labels]['.$lang['id'].']',
        array_key_exists($lang['id'], $dlabels)? $dlabels[$lang['id']]: '')
        ->addValidateRule('required', true);
    $f->addInputField('text', $this->locale->_('attrs_form_label'), 'form_label_'.$lang['id'], 'data[form_labels]['.$lang['id'].']',
        array_key_exists($lang['id'], $flabels)? $flabels[$lang['id']]: '')
        ->addValidateRule('required', true);
    $f->endGroup();
endforeach;
$f->addHiddenField('main_data_type', 'data[data_type]', $this->model->getDataType());
$f->addHiddenField('main_input_type', 'data[input_type]', $this->model->getInputType());
$f->addHiddenField('main_input_type', 'data[id]', $this->model->getId());

$this->assign('form', $f);
$this->assign('form', $f);
echo $this->includeTemplate('layout\form');