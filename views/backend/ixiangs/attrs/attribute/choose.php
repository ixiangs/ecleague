<?php
$nbs = array();
if($this->request->getQuery('set_id')){
    $nbs[] = $this->html->anchor($this->locale->_('back'), $this->router->buildUrl('attribute-set/groups', array(
        'id'=>$this->request->getQuery('set_id')
    )));
}else{
    $nbs[] = $this->html->anchor($this->locale->_('back'), $this->router->buildUrl('list'));
}
$this->assign('navigationBar', $nbs);

$this->assign('toolbar', array(
    $this->html->button('button', $this->locale->_('next_step'), 'btn btn-primary')->setAttribute('data-submit', 'form1')
));

$f = $this->html->form()->setAttribute(array(
    'action'=>$this->router->buildUrl('add'),
    'method'=>'get')
);

$f->newField($this->locale->_('attrs_component'), true,
                $this->html->select('component_id', 'component_id', $this->request->getQuery('component_id'), $this->components));
//                    ->addValidateRule('required', true));
$f->newField($this->locale->_('attrs_data_type'), true,
    $this->html->select('data_type', 'data_type', null, array(
    \Ixiangs\Attrs\Constant::DATA_TYPE_STRING=>$this->locale->_('attrs_data_type_string'),
    \Ixiangs\Attrs\Constant::DATA_TYPE_INTEGER=>$this->locale->_('attrs_data_type_integer'),
    \Ixiangs\Attrs\Constant::DATA_TYPE_NUMBER=>$this->locale->_('attrs_data_type_number'),
    \Ixiangs\Attrs\Constant::DATA_TYPE_BOOLEAN=>$this->locale->_('attrs_data_type_boolean'),
    \Ixiangs\Attrs\Constant::DATA_TYPE_DATE=>$this->locale->_('attrs_data_type_date'),
    \Ixiangs\Attrs\Constant::DATA_TYPE_EMAIL=>$this->locale->_('attrs_data_type_email'),
    \Ixiangs\Attrs\Constant::DATA_TYPE_ARRAY=>$this->locale->_('attrs_data_type_array')))
        ->setCaption('')
        ->addValidateRule('required', true));
$f->newField($this->locale->_('attrs_input_type'), true,
    $this->html->select('input_type', 'input_type', null, array(
    \Ixiangs\Attrs\Constant::INPUT_TYPE_TEXTBOX=>$this->locale->_('attrs_input_type_textbox'),
    \Ixiangs\Attrs\Constant::INPUT_TYPE_TEXTAREA=>$this->locale->_('attrs_input_type_textarea'),
    \Ixiangs\Attrs\Constant::INPUT_TYPE_EDITOR=>$this->locale->_('attrs_input_type_editor'),
    \Ixiangs\Attrs\Constant::INPUT_TYPE_SELECT=>$this->locale->_('attrs_input_type_select'),
    \Ixiangs\Attrs\Constant::INPUT_TYPE_OPTION_LIST=>$this->locale->_('attrs_input_type_option_list'),
    \Ixiangs\Attrs\Constant::INPUT_TYPE_DATE_PICKER=>$this->locale->_('attrs_input_type_datepicker')))
        ->setCaption('')
        ->addValidateRule('required', true));
//$f->addHidden('set_id', 'set_id', $this->request->getQuery('set_id'));
$this->assign('form', $f);
echo $this->includeTemplate('layout\form');