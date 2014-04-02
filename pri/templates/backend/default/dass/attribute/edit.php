<?php
$this->assign('breadcrumb', array(
    $this->html->anchor($this->locale->_('dass_manage')),
    $this->html->anchor($this->locale->_('dass_add_attribute'))
));

$this->assign('navigationBar', array(
    $this->html->anchor($this->locale->_('back'), $this->router->buildUrl('list'))
));

$this->assign('toolbar', array(
    $this->html->button('button', $this->locale->_('next_step'), 'btn btn-primary')->setAttribute('data-submit', 'form1')
));

$f = $this->html->form()->setAttribute();
//$f->addSelectField(array(
//    \Core\Dass\Model\AttributeModel::DATA_TYPE_STRING=>$this->locale->_('dass_data_type_string'),
//    \Core\Dass\Model\AttributeModel::DATA_TYPE_INTEGER=>$this->locale->_('dass_data_type_integer'),
//    \Core\Dass\Model\AttributeModel::DATA_TYPE_NUMBER=>$this->locale->_('dass_data_type_number'),
//    \Core\Dass\Model\AttributeModel::DATA_TYPE_BOOLEAN=>$this->locale->_('dass_data_type_boolean'),
//    \Core\Dass\Model\AttributeModel::DATA_TYPE_DATE=>$this->locale->_('dass_data_type_date'),
//    \Core\Dass\Model\AttributeModel::DATA_TYPE_EMAIL=>$this->locale->_('dass_data_type_email'),
//    \Core\Dass\Model\AttributeModel::DATA_TYPE_ARRAY=>$this->locale->_('dass_data_type_array')
//), $this->locale->_('dass_data_type'), 'data_type', 'data_type');
//$f->addSelectField(array(
//    \Core\Dass\Model\AttributeModel::INPUT_TYPE_TEXTBOX=>$this->locale->_('dass_input_type_textbox'),
//    \Core\Dass\Model\AttributeModel::INPUT_TYPE_TEXTAREA=>$this->locale->_('dass_input_type_textarea'),
//    \Core\Dass\Model\AttributeModel::INPUT_TYPE_DROPDOWN=>$this->locale->_('dass_input_type_dropdown'),
//    \Core\Dass\Model\AttributeModel::INPUT_TYPE_LISTBOX=>$this->locale->_('dass_input_type_listbox'),
//    \Core\Dass\Model\AttributeModel::INPUT_TYPE_DATE_PICKER=>$this->locale->_('dass_input_type_datepicker'),
//    \Core\Dass\Model\AttributeModel::INPUT_TYPE_CHECKBOX_LIST=>$this->locale->_('dass_input_type_checkboxlist'),
//    \Core\Dass\Model\AttributeModel::INPUT_TYPE_RADIO_LIST=>$this->locale->_('dass_input_type_raidolist')
//), $this->locale->_('dass_input_type'), 'input_type', 'input_type');
$f = $this->html->form();
$f->addInputField('text', $this->locale->_('code'), 'code', 'code', $this->models[0]->getCode())
    ->addValidateRule('required', true);
$f->addSelectField(array('1'=>$this->locale->_('yes'), '0'=>$this->locale->_('no')),
    $this->locale->_('dass_indexable'), 'indexable', 'indexable', $this->models[0]->getEnabled());
$f->addSelectField(array('1'=>$this->locale->_('yes'), '0'=>$this->locale->_('no')),
    $this->locale->_('dass_required'), 'required', 'required', $this->models[0]->getEnabled());
$f->addSelectField(array('1'=>$this->locale->_('yes'), '0'=>$this->locale->_('no')),
    $this->locale->_('enable'), 'enabled', 'enabled', $this->models[0]->getEnabled());
foreach($this->locale->getLanguages() as $lang):
    $vmodel = null;
    foreach($this->models as $m):
        if($m->getVersionKey() == $lang['code']):
            $vmodel = $m;
            break;
        endif;
    endforeach;
$f->addInputField('text', $this->locale->_('name'), 'version_'.$lang['code'].'_name', 'versions['.$lang['code'].'][name]', $vmodel->getName())
    ->addValidateRule('required', true)
    ->setLeftAddon($lang['name']);
$f->addInputField('text', $this->locale->_('dass_display_label'), 'version_'.$lang['code'].'_display_label', 'versions['.$lang['code'].'][display_label]', $vmodel->getDisplayLabel())
    ->addValidateRule('required', true)
    ->setLeftAddon($lang['name']);
$f->addInputField('text', $this->locale->_('dass_form_label'), 'version_'.$lang['code'].'_form_label', 'versions['.$lang['code'].'][form_label]', $vmodel->getFormLabel())
    ->addValidateRule('required', true)
    ->setLeftAddon($lang['name']);
$f->addHiddenField('version_'.$lang['code'].'_id', 'versions['.$lang['code'].'][id]', $vmodel->getVersionId());
endforeach;

$this->assign('form', $f);
$this->assign('form', $f);
echo $this->includeTemplate('layout\form');