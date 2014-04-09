<?php
$this->assign('breadcrumb', array(
    $this->html->anchor($this->locale->_('dass_manage')),
    $this->html->anchor($this->locale->_('dass_add_attribute'))
));

$this->assign('navigationBar', array(
    $this->html->anchor($this->locale->_('back'), $this->router->buildUrl('list'))
));

$this->assign('toolbar', array(
    $this->html->button('button', $this->locale->_('save'), 'btn btn-primary')->setAttribute('data-submit', 'form1')
));

$f = $this->html->form()->setAttribute();
$dataTypes = array(
    \Core\Dass\Model\AttributeModel::DATA_TYPE_STRING=>$this->locale->_('dass_data_type_string'),
    \Core\Dass\Model\AttributeModel::DATA_TYPE_INTEGER=>$this->locale->_('dass_data_type_integer'),
    \Core\Dass\Model\AttributeModel::DATA_TYPE_NUMBER=>$this->locale->_('dass_data_type_number'),
    \Core\Dass\Model\AttributeModel::DATA_TYPE_BOOLEAN=>$this->locale->_('dass_data_type_boolean'),
    \Core\Dass\Model\AttributeModel::DATA_TYPE_DATE=>$this->locale->_('dass_data_type_date'),
    \Core\Dass\Model\AttributeModel::DATA_TYPE_EMAIL=>$this->locale->_('dass_data_type_email'),
    \Core\Dass\Model\AttributeModel::DATA_TYPE_ARRAY=>$this->locale->_('dass_data_type_array')
);
$inputTypes = array(
    \Core\Dass\Model\AttributeModel::INPUT_TYPE_TEXTBOX=>$this->locale->_('dass_input_type_textbox'),
    \Core\Dass\Model\AttributeModel::INPUT_TYPE_TEXTAREA=>$this->locale->_('dass_input_type_textarea'),
    \Core\Dass\Model\AttributeModel::INPUT_TYPE_DROPDOWN=>$this->locale->_('dass_input_type_dropdown'),
    \Core\Dass\Model\AttributeModel::INPUT_TYPE_LISTBOX=>$this->locale->_('dass_input_type_listbox'),
    \Core\Dass\Model\AttributeModel::INPUT_TYPE_DATE_PICKER=>$this->locale->_('dass_input_type_datepicker'),
    \Core\Dass\Model\AttributeModel::INPUT_TYPE_CHECKBOX_LIST=>$this->locale->_('dass_input_type_checkboxlist'),
    \Core\Dass\Model\AttributeModel::INPUT_TYPE_RADIO_LIST=>$this->locale->_('dass_input_type_raidolist')
);
$f = $this->html->groupedForm();

$f->beginGroup('tab_base', $this->locale->_('base_info'));
$f->addLabelField($this->locale->_('dass_data_type'), $dataTypes[$this->model->getDataType()]);
$f->addLabelField($this->locale->_('dass_input_type'), $inputTypes[$this->model->getInputType()]);
$f->addInputField('text', $this->locale->_('code'), 'code', 'main[code]', $this->model->getCode())
    ->addValidateRule('required', true);
$f->addSelectField(array('1'=>$this->locale->_('yes'), '0'=>$this->locale->_('no')),
    $this->locale->_('dass_indexable'), 'indexable', 'main[indexable]', $this->model->getEnabled());
$f->addSelectField(array('1'=>$this->locale->_('yes'), '0'=>$this->locale->_('no')),
    $this->locale->_('dass_required'), 'required', 'main[required]', $this->model->getEnabled());
$f->addSelectField(array('1'=>$this->locale->_('yes'), '0'=>$this->locale->_('no')),
    $this->locale->_('enable'), 'enabled', 'main[enabled]', $this->model->getEnabled());
$f->endGroup();

$vs = $this->model->getValidateSetting();
switch($this->model->getInputType()):
    case \Core\Dass\Model\AttributeModel::INPUT_TYPE_TEXTBOX:
        switch($this->model->getDataType()):
            case \Core\Dass\Model\AttributeModel::DATA_TYPE_INTEGER:
                $f->beginGroup('tab_validate', $this->locale->_('dass_validate_setting'));
                $f->addInputField('text', $this->locale->_('dass_min_value'), 'min_value', 'main[validate_setting][min_value]', $vs['min_value'])
                    ->addValidateRule('integer', true);
                $f->addInputField('text', $this->locale->_('dass_max_value'), 'max_value', 'main[validate_setting][max_value]', $vs['max_value'])
                    ->addValidateRule('integer', true)->addValidateRule('greatto', '#min_value', $this->locale->_('dass_max_great_min'));
                $f->endGroup();
            break;
            case \Core\Dass\Model\AttributeModel::DATA_TYPE_NUMBER:
                $f->beginGroup('tab_validate', $this->locale->_('dass_validate_setting'));
                $f->addInputField('text', $this->locale->_('dass_min_value'), 'min_value', 'main[validate_setting][min_value]', $vs['min_value'])
                    ->addValidateRule('number', true);
                $f->addInputField('text', $this->locale->_('dass_max_value'), 'max_value', 'main[validate_setting][max_value]', $vs['max_value'])
                    ->addValidateRule('number', true)->addValidateRule('greatto', '#min_value', $this->locale->_('dass_max_great_min'));
                $f->endGroup();
                break;
            case \Core\Dass\Model\AttributeModel::DATA_TYPE_STRING:
                $f->beginGroup('tab_validate', $this->locale->_('dass_validate_setting'));
                $f->addInputField('text', $this->locale->_('dass_max_length'), 'max_length', 'main[validate_setting][max_length]', $vs['max_length'])
                    ->addValidateRule('integer', true);
                $f->endGroup();
            break;
        endswitch;
        break;
endswitch;

foreach($this->locale->getLanguages() as $lang):
    $vmodel = null;
    foreach($this->model->getVersions() as $m):
        if($m->getLanguageId() == $lang['id']):
            $vmodel = $m;
            break;
        endif;
    endforeach;
    $f->beginGroup('tab_lang_'.$lang['code'], $lang['name']);
    $f->addInputField('text', $this->locale->_('name'), 'version_'.$lang['id'].'_name', 'versions['.$lang['id'].'][name]', $vmodel->getName())
        ->addValidateRule('required', true);
    $f->addInputField('text', $this->locale->_('dass_display_label'), 'version_'.$lang['id'].'_display_label', 'versions['.$lang['id'].'][display_label]', $vmodel->getDisplayLabel())
        ->addValidateRule('required', true);
    $f->addInputField('text', $this->locale->_('dass_form_label'), 'version_'.$lang['id'].'_form_label', 'versions['.$lang['id'].'][form_label]', $vmodel->getFormLabel())
        ->addValidateRule('required', true);
    $f->endGroup();
    $f->addHiddenField('version_'.$lang['id'].'_version_id', 'versions['.$lang['id'].'][version_id]', $vmodel->getVersionId());
//    $f->addHiddenField('version_'.$lang['code'].'_language_id', 'versions['.$lang['code'].'][language_id]', $vmodel->getLanguageId());
endforeach;
$f->addHiddenField('main_data_type', 'main[data_type]', $this->model->getDataType());
$f->addHiddenField('main_input_type', 'main[input_type]', $this->model->getInputType());
$f->addHiddenField('main_input_type', 'main[id]', $this->model->getId());

$this->assign('form', $f);
$this->assign('form', $f);
echo $this->includeTemplate('layout\form');