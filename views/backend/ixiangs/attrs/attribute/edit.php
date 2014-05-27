<?php
$this->assign('navigationBar', array(
    $this->html->anchor($this->locale->_('back'), $this->router->getHistoryUrl('list'))
));

$this->assign('toolbar', array(
    $this->html->button('button', $this->locale->_('save'), 'btn btn-primary')
            ->setAttribute('data-submit', 'form1')
));

$dataTypes = array(
    \Ixiangs\Attrs\Constant::DATA_TYPE_STRING=>$this->locale->_('attrs_data_type_string'),
    \Ixiangs\Attrs\Constant::DATA_TYPE_INTEGER=>$this->locale->_('attrs_data_type_integer'),
    \Ixiangs\Attrs\Constant::DATA_TYPE_NUMBER=>$this->locale->_('attrs_data_type_number'),
    \Ixiangs\Attrs\Constant::DATA_TYPE_BOOLEAN=>$this->locale->_('attrs_data_type_boolean'),
    \Ixiangs\Attrs\Constant::DATA_TYPE_DATE=>$this->locale->_('attrs_data_type_date'),
    \Ixiangs\Attrs\Constant::DATA_TYPE_EMAIL=>$this->locale->_('attrs_data_type_email'),
    \Ixiangs\Attrs\Constant::DATA_TYPE_ARRAY=>$this->locale->_('attrs_data_type_array')
);
$inputTypes = array(
    \Ixiangs\Attrs\Constant::INPUT_TYPE_TEXTBOX=>$this->locale->_('attrs_input_type_textbox'),
    \Ixiangs\Attrs\Constant::INPUT_TYPE_TEXTAREA=>$this->locale->_('attrs_input_type_textarea'),
    \Ixiangs\Attrs\Constant::INPUT_TYPE_EDITOR=>$this->locale->_('attrs_input_type_editor'),
    \Ixiangs\Attrs\Constant::INPUT_TYPE_SELECT=>$this->locale->_('attrs_input_type_select'),
    \Ixiangs\Attrs\Constant::INPUT_TYPE_OPTION_LIST=>$this->locale->_('attrs_input_type_option_list'),
    \Ixiangs\Attrs\Constant::INPUT_TYPE_DATE_PICKER=>$this->locale->_('attrs_input_type_datepicker')
);
$f = $this->html->groupedForm()
        ->setAttribute('action', $this->router->buildUrl('save', '*'));
$f->beginGroup('tab_base', $this->locale->_('base_info'));
$f->newField('')->setRenderer(function($field) use($dataTypes, $inputTypes){
    $res = array('<div class="form-group">');
    $res[] = '<label class="control-label col-md-4" style="padding-left:0">'.$this->locale->_('attrs_data_type').':'.$dataTypes[$this->model->getDataType()].'</label>';
    $res[] = '<label class="control-label col-md-4" style="padding-left:0">'.$this->locale->_('attrs_input_type').':'.$inputTypes[$this->model->getInputType()].'</label>';
    $res[] = '<label class="control-label col-md-4" style="padding-left:0">'.$this->locale->_('attrs_component').':'.(is_null($this->component)? $this->locale->_('attrs_common'): $this->component->getName()).'</label>';
    $res[] = '</div>';
    return implode('', $res);
});

$f->newField($this->locale->_('name'), true,
    $this->html->textbox('name', 'data[name]', $this->model->getName())
        ->addValidateRule('required', true));
$f->newField($this->locale->_('text'), true,
    $this->html->textbox('label', 'data[label]', $this->model->getLabel())
        ->addValidateRule('required', true));
//$f->newField($this->locale->_('attrs_indexable'), true,
//    $this->html->select('indexable', 'data[indexable]', $this->model->getIndexable(),
//        array('1'=>$this->locale->_('yes'), '0'=>$this->locale->_('no'))));
//
//$f->newField($this->locale->_('enabled'), true,
//    $this->html->select('enabled', 'data[enabled]', $this->model->getEnabled(),
//        array('1'=>$this->locale->_('yes'), '0'=>$this->locale->_('no'))));

$vs = array_merge(array(
    'min_value'=>'',
    'max_value'=>'',
    'max_length'=>'',
    'multiple'=>'',
    'size'=>'1'
), $this->model->getInputSetting(array()));

$f->newField($this->locale->_('memo'), true,
    $this->html->textbox('memo', 'data[memo]', $this->model->getMemo()));
switch($this->model->getInputType()):
    case \Ixiangs\Attrs\Constant::INPUT_TYPE_TEXTBOX:
        switch($this->model->getDataType()):
            case \Ixiangs\Attrs\Constant::DATA_TYPE_INTEGER:
                $f->newField($this->locale->_('attrs_min_value'), false,
                    $this->html->textbox('min_value', 'data[input_setting][min_value]', $vs['min_value'])
                    ->addValidateRule('integer', true));
                $f->newField($this->locale->_('attrs_max_value'), false,
                    $this->html->textbox('max_value', 'data[input_setting][max_value]', $vs['max_value'])
                    ->addValidateRule('integer', true)
                    ->addValidateRule('greatto', '#min_value', $this->locale->_('attrs_max_great_min')));
                break;
            case \Ixiangs\Attrs\Constant::DATA_TYPE_NUMBER:
                $f->newField($this->locale->_('attrs_min_value'), false,
                    $this->html->textbox('min_value', 'data[input_setting][min_value]', $vs['min_value'])
                    ->addValidateRule('number', true));
                $f->newField($this->locale->_('attrs_max_value'), false,
                    $this->html->textbox('max_value', 'data[input_setting][max_value]', $vs['max_value'])
                    ->addValidateRule('number', true)
                    ->addValidateRule('greatto', '#min_value', $this->locale->_('attrs_max_great_min')));
                break;
            case \Ixiangs\Attrs\Constant::DATA_TYPE_STRING:
                $f->newField($this->locale->_('attrs_max_length'), false,
                    $this->html->textbox('max_length', 'data[input_setting][max_length]', $vs['max_length'])
                    ->addValidateRule('integer', true));
                break;
        endswitch;
        break;
    case \Ixiangs\Attrs\Constant::INPUT_TYPE_SELECT:
    case \Ixiangs\Attrs\Constant::INPUT_TYPE_OPTION_LIST:
        $f->newField($this->locale->_('attrs_multiple'), true,
            $this->html->select('multiple', 'data[input_setting][multiple]', $vs['multiple'],
            array('1'=>$this->locale->_('yes'), '0'=>$this->locale->_('no')))
            ->addValidateRule('required', true));
        if($this->model->getInputType() == \Ixiangs\Attrs\Constant::INPUT_TYPE_SELECT):
            $f->newField($this->locale->_('attrs_multiple_size'), true,
                $this->html->textbox('size', 'data[input_setting][size]', $vs['size'])
                ->addValidateRule('required', true));
            $f->newField($this->locale->_('attrs_default_option'), true,
                $this->html->select('default_option', 'data[input_setting][default_option]', $vs['multiple'],
                    array('empty'=>$this->locale->_('attrs_empty_option'), 'first'=>$this->locale->_('attrs_first_option')))
                    ->addValidateRule('required', true));
        endif;
        break;
endswitch;
$f->endGroup();

switch($this->model->getInputType()):
    case \Ixiangs\Attrs\Constant::INPUT_TYPE_SELECT:
    case \Ixiangs\Attrs\Constant::INPUT_TYPE_OPTION_LIST:
        $f->beginGroup('tab_option', $this->locale->_('attrs_option'));
        $options = $this->model->getOptions();
        $f->newField('', false)->setRenderer(function() use($options){
            $res = array('<div class="form-group"><table id="option_table" class="table table-bordered" style="width:auto;">');
            $res[] = '<thead><tr><th>'.$this->locale->_('attrs_option_value').'</th>';
            $res[] = '<th>'.$this->locale->_('text').'</th>';
            $res[] = '<th><a href="javascript:void(0);" id="add_option">'.$this->locale->_('add').'</a></th></tr></thead><tbody>';
            if(count($options) == 0){
                $res[] = '<tr><td><input type="text" name="new_options[0][value]" value=""/></td>';
                $res[] = '<td><input type="text" name="new_options[0][label]" value=""/></td>';
                $res[] = '<td><a href="javascript:void(0);" data-action="delete">'.$this->locale->_('delete').'</a></td>';
                $res[] = '</tr>';
            }else{
                foreach($options as $index=>$op){
                    if($op->getId()){
                        $res[] = '<tr><td><input type="text" class="option-value" name="edit_options['.$op->getId().'][value]" value="'.$op->getValue().'"/></td>';
                        $res[] = '<td><input type="text" class="option-value" name="edit_options['.$op->getId().'][label]" value="'.$op->getLabel().'"/></td>';
                        $res[] = '<td><a href="javascript:void(0);" data-id="'.$op->getId().'" data-action="delete">'.$this->locale->_('delete').'</a></td>';
                        $res[] = '</tr>';
                    }else{
                        $res[] = '<tr><td><input type="text" class="option-value" name="new_options['.$index.'][value]" value="'.$op->getValue().'"/></td>';
                        $res[] = '<td><input type="text" class="option-value" name="new_options['.$index.'][label]" value="'.$op->getLabel().'"/></td>';
                        $res[] = '<td><a href="javascript:void(0);" data-action="delete">'.$this->locale->_('delete').'</a></td>';
                        $res[] = '</tr>';
                    }

                }
            }
            $res[] .= '</tbody></table>';
            $res[] .= '<input type="hidden" name="option_values" data-validate-option-required="true" data-validate-option-repeated="true" />';
            $res[] .= '</div>';
            return implode('', $res);
        });
        $f->endGroup();
        break;
endswitch;

$f->addHidden('data_type', 'data[data_type]', $this->model->getDataType());
$f->addHidden('input_type', 'data[input_type]', $this->model->getInputType());
$f->addHidden('component_id', 'data[component_id]', $this->model->getComponentId());
$f->addHidden('id', 'data[id]', $this->model->getId());

$this->assign('form', $f);
$this->beginScript('attrsEditAttibute');
?>
    <script language="javascript">
        Toy.Validation.rules['option-required'] = new (new Class({
            match: function (field) {
                var $input = $(field.inputs[0]);
                var p = $input.attr('data-validate-option-required');
                return p ? p : false;
            },
            check: function (field, params) {
                var pass = true;
                $('#option_table input').each(function(){
                    if($(this).val().trim().length == 0){
                        pass = false;
                    }
                });
                return pass;
            },
            message: function (field, params) {
                return '<?php echo $this->locale->_('attrs_option_required'); ?>';
            }
        }))();
        Toy.Validation.rules['option_repeated'] = new (new Class({
            match: function (field) {
                var $input = $(field.inputs[0]);
                var p = $input.attr('data-validate-option-repeated');
                return p ? p : false;
            },
            check: function (field, params) {
                var pass = true;
                var values = [];
                $('#option_table .option-value').each(function(){
                    if(values.contains($(this).val())){
                        pass = false;
                    }
                    values.push($(this).val());
                });
                return pass;
            },
            message: function (field, params) {
                return '<?php echo $this->locale->_('attrs_option_repeated'); ?>';
            }
        }))();
        var curIndex = <?php echo count($this->model->options); ?>;
        var optionHtml = '<tr><td><input type="text" class="option-value" name="new_options[{index}][value]" value=""/></td>';
        optionHtml += '<td><input type="text" class="option-value" name="new_options[{index}][label]" value=""/></td>';
        optionHtml += '<td><a href="javascript:void(0);" data-action="delete"><?php echo $this->locale->_('delete'); ?></a></td>';
        optionHtml += '</tr>';

        $('#option_table tbody').delegate('a', 'click', function(){
            $('#form1').append('<input type="hidden" name="delete_options[]" value="' + $(this).attr('data-id') + '"/>')
            $(this).parents('tr').remove();
        });
        $('#add_option').click(function () {
            ++curIndex;
            $('#option_table tbody').append(optionHtml.substitute({index:curIndex}));
        });
    </script>
<?php
$this->endScript();
echo $this->includeTemplate('layout\form');