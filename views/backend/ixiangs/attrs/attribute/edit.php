<?php
$langId = $this->locale->getLanguageId();
$this->assign('breadcrumb', array(
    $this->html->anchor($this->locale->_('attrs_manage')),
    $this->html->anchor($this->locale->_('attrs_add_attribute'))
));

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
    $this->html->dropdownButton(
        $this->html->button('button', $this->locale->_('save'), 'btn btn-primary')
            ->setAttribute('id', 'save_form'))
            ->addChild(
                $this->html->anchor($this->locale->_('save_and_new'), 'javascript:void(0)', 'btn btn-primary')
                    ->setAttribute('id', 'save_new')
            )
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
    $res[] = '<label class="control-label col-md-4" style="padding-left:0">'.$this->locale->_('attrs_component').':'.$this->component->getName().'</label>';
    $res[] = '</div>';
    return implode('', $res);
});

$f->newField($this->locale->_('name'), true,
    $this->html->textbox('name', 'data[name]', $this->model->getName())
        ->addValidateRule('required', true));
$f->newField($this->locale->_('attrs_indexable'), true,
    $this->html->select('indexable', 'data[indexable]', $this->model->getEnabled(),
        array('1'=>$this->locale->_('yes'), '0'=>$this->locale->_('no'))));
$f->newField($this->locale->_('attrs_localizable'), true,
    $this->html->select('localizable', 'data[localizable]', $this->model->getLocalizable(),
        array('1'=>$this->locale->_('yes'), '0'=>$this->locale->_('no'))));

$f->newField($this->locale->_('enabled'), true,
    $this->html->select('enabled', 'data[enabled]', $this->model->getEnabled(),
        array('1'=>$this->locale->_('yes'), '0'=>$this->locale->_('no'))));

$vs = array_merge(array(
    'min_value'=>'',
    'max_value'=>'',
    'max_length'=>'',
    'multiple'=>'',
    'size'=>'1'
), $this->model->getInputSetting(array()));

$f->newField($this->locale->_('attrs_required'), true,
    $this->html->select('required', 'data[required]', $this->model->getEnabled(),
        array('1'=>$this->locale->_('yes'), '0'=>$this->locale->_('no'))));
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

$langs = $this->locale->getAllLanguages();
switch($this->model->getInputType()):
    case \Ixiangs\Attrs\Constant::INPUT_TYPE_SELECT:
    case \Ixiangs\Attrs\Constant::INPUT_TYPE_OPTION_LIST:
        $f->beginGroup('tab_option', $this->locale->_('attrs_option'));
        $options = $this->model->getOptions(array());
        $f->newField('', false)->setRenderer(function() use($options, $langId, $langs){
            $res = array('<div class="form-group"><table id="option_table" class="table table-bordered" style="width:auto;">');
            $res[] = '<thead><tr><th>'.$this->locale->_('attrs_option_value').'</th>';
            foreach($langs as $lang){
                $res[] = '<th>'.$lang['name'].'</th>';
            }
            $res[] = '<th><a href="javascript:void(0);" id="add_option">'.$this->locale->_('add').'</a></th></tr></thead><tbody>';
            if(count($options) == 0){
                $res[] = '<tr><td><input type="text" value=""/></td>';
                foreach($langs as $lang){
                    $res[] = '<td><input data-lang="'.$lang['id'].'" type="text" value=""/></td>';
                }
                $res[] = '<td><a href="javascript:void(0);" data-action="delete">'.$this->locale->_('delete').'</a></td>';
                $res[] = '</tr>';
            }else{
                foreach($options as $op){
                    $res[] = '<tr><td><input type="text" value="'.$op['value'].'"/></td>';
                    foreach($langs as $lang){
                        $res[] = '<td><input type="text" data-lang="'.$lang['id'].'" value="'.$op['texts'][$lang['id']].'"/></td>';
                    }
                    $res[] = '<td><a href="javascript:void(0);" data-action="delete">'.$this->locale->_('delete').'</a></td>';
                    $res[] = '</tr>';
                }
            }
            $res[] .= '</tbody></table>';
            $res[] .= '<input type="hidden" id="options" name="options" value="" data-validate-required="true"/>';
            $res[] .= '</div>';
            return implode('', $res);
        });
        $f->endGroup();
        break;
endswitch;

$names = $this->model->getNames(array());
$dlabels = $this->model->getDisplayText(array());
$flabels = $this->model->getMemo(array());
foreach($langs as $lang):
    $f->beginGroup('tab_lang_'.$lang['code'], $lang['name']);
    $f->newField($this->locale->_('text'), true,
        $this->html->textbox('display_label_'.$lang['id'], 'data[label]['.$lang['id'].']',
        array_key_exists($lang['id'], $dlabels)? $dlabels[$lang['id']]: '')
        ->addValidateRule('required', true));
    $f->newField($this->locale->_('memo'), true,
        $this->html->textbox('memo_'.$lang['id'], 'data[memo]['.$lang['id'].']',
        array_key_exists($lang['id'], $flabels)? $flabels[$lang['id']]: ''));
    $f->endGroup();
endforeach;
$f->addHidden('data_type', 'data[data_type]', $this->model->getDataType());
$f->addHidden('input_type', 'data[input_type]', $this->model->getInputType());
$f->addHidden('component_id', 'data[component_id]', $this->model->getComponentId());
$f->addHidden('next_action', 'next_action', '');
$f->addHidden('id', 'data[id]', $this->model->getId());

$this->assign('form', $f);
$this->beginBlock('footerjs');
?>
    <script language="javascript">
        var curIndex = 0;
        var optionHtml = '<tr><td><input type="text" value=""/></td>';
        <?php foreach($langs as $lang): ?>
        optionHtml += '<td><input data-lang="<?php echo $lang['id']; ?>" type="text" value=""/></td>';
        <?php endforeach; ?>
        optionHtml += '<td><a href="javascript:void(0);" data-action="delete"><?php echo $this->locale->_('delete'); ?></a></td>';
        optionHtml += '</tr>';

        $('#option_table tbody').delegate('a', 'click', function(){
           $(this).parents('tr').remove();
        });
        $('#add_option').click(function () {
            $('#option_table tbody').append(optionHtml);
        });
        $('#save_form,#save_new').click(function(){
            $('#next_action').val($(this).attr('id') == 'save_form'? '': 'new');
            var pass = true;
            $('#option_table input').each(function(){
                if($(this).val().trim().length == 0){
                    pass = false;
                }
            });
            if(!pass){
                $('#options').val('');
                $('#form1').data('validator').validate();
                return;
            }

            $('#options').val('1');
            $('.hidden_op').remove();
            var trIndex = 0;
            $('#option_table tbody tr').each(function(){
                trIndex++;
                $(this).find('input').each(function(index){
                    if(index == 0){
                        $('#form1').append('<input class="hidden_op" type="hidden" name="data[options][' + trIndex + '][value]" value="' + $(this).val() + '"/>');
                    }else{
                        $('#form1').append('<input class="hidden_op" type="hidden" name="data[options][' + trIndex + '][texts][' + $(this).attr('data-lang') + ']" value="' + $(this).val() + '"/>');
                    }
                });
            });
            $('#form1').submit();
        });
    </script>
<?php
$this->endBlock();
echo $this->includeTemplate('layout\form');