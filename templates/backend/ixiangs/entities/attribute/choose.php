<?php
$nbs = array();
if($this->request->getQuery('set_id')){
    $nbs[] = $this->html->anchor($this->localize->_('back'), $this->router->buildUrl('attribute-set/groups', array(
        'id'=>$this->request->getQuery('set_id')
    )));
}else{
    $nbs[] = $this->html->anchor($this->localize->_('back'), $this->router->buildUrl('list'));
}
$this->assign('navigationBar', $nbs);

$this->assign('toolbar', array(
    $this->html->button('button', $this->localize->_('next_step'), 'btn btn-primary')->setAttribute('data-submit', 'form1')
));

$f = $this->html->form()->setAttribute(array(
    'action'=>$this->router->buildUrl('add'),
    'method'=>'get')
);

$f->newField($this->localize->_('source'), true,
                $this->html->select('component_id', 'component_id', $this->request->getQuery('component_id'), $this->components));
//                    ->addValidateRule('required', true));
$f->newField($this->localize->_('entities_data_type'), true,
    $this->html->select('data_type', 'data_type', null, array(
    \Ixiangs\Entities\Constant::DATA_TYPE_STRING=>$this->localize->_('entities_data_type_string'),
    \Ixiangs\Entities\Constant::DATA_TYPE_INTEGER=>$this->localize->_('entities_data_type_integer'),
    \Ixiangs\Entities\Constant::DATA_TYPE_NUMBER=>$this->localize->_('entities_data_type_number'),
    \Ixiangs\Entities\Constant::DATA_TYPE_BOOLEAN=>$this->localize->_('entities_data_type_boolean'),
    \Ixiangs\Entities\Constant::DATA_TYPE_DATE=>$this->localize->_('entities_data_type_date'),
    \Ixiangs\Entities\Constant::DATA_TYPE_EMAIL=>$this->localize->_('entities_data_type_email'),
    \Ixiangs\Entities\Constant::DATA_TYPE_ARRAY=>$this->localize->_('entities_data_type_array')))
        ->setCaption('')
        ->addValidateRule('required', true));
$f->newField($this->localize->_('entities_input_type'), true,
    $this->html->select('input_type', 'input_type', null, array(
    \Ixiangs\Entities\Constant::INPUT_TYPE_TEXTBOX=>$this->localize->_('entities_input_type_textbox'),
    \Ixiangs\Entities\Constant::INPUT_TYPE_TEXTAREA=>$this->localize->_('entities_input_type_textarea'),
    \Ixiangs\Entities\Constant::INPUT_TYPE_EDITOR=>$this->localize->_('entities_input_type_editor'),
    \Ixiangs\Entities\Constant::INPUT_TYPE_SELECT=>$this->localize->_('entities_input_type_select'),
    \Ixiangs\Entities\Constant::INPUT_TYPE_OPTION_LIST=>$this->localize->_('entities_input_type_option_list'),
    \Ixiangs\Entities\Constant::INPUT_TYPE_DATE_PICKER=>$this->localize->_('entities_input_type_datepicker')))
        ->setCaption('')
        ->addValidateRule('required', true));
//$f->addHidden('set_id', 'set_id', $this->request->getQuery('set_id'));
$this->assign('form', $f);
echo $this->includeTemplate('layout\form');