<?php
$langId = $this->locale->getCurrentLanguageId();
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
    \Core\Attrs\Model\AttributeModel::INPUT_TYPE_EDITOR=>$this->locale->_('attrs_input_type_editor'),
    \Core\Attrs\Model\AttributeModel::INPUT_TYPE_SELECT=>$this->locale->_('attrs_input_type_select'),
    \Core\Attrs\Model\AttributeModel::INPUT_TYPE_OPTION_LIST=>$this->locale->_('attrs_input_type_option_list'),
    \Core\Attrs\Model\AttributeModel::INPUT_TYPE_DATE_PICKER=>$this->locale->_('attrs_input_type_datepicker')
);
$f = $this->html->groupedForm()
        ->setAttribute('action', $this->router->buildUrl('save', '*'));
$f->beginGroup('tab_base', $this->locale->_('base_info'));
$f->addStaticField($this->locale->_('attrs_data_type'), $dataTypes[$this->model->getDataType()]);
$f->addStaticField($this->locale->_('attrs_input_type'), $inputTypes[$this->model->getInputType()]);
$f->addStaticField($this->locale->_('attrs_component'), $this->component->getName());

$f->addInputField('text', $this->locale->_('name'), 'name', 'data[name]', $this->model->getName())
    ->addValidateRule('required', true);
$f->addSelectField(array('1'=>$this->locale->_('yes'), '0'=>$this->locale->_('no')),
    $this->locale->_('attrs_indexable'), 'indexable', 'data[indexable]', $this->model->getEnabled());
$f->addSelectField(array('1'=>$this->locale->_('yes'), '0'=>$this->locale->_('no')),
    $this->locale->_('attrs_localizable'), 'localizable', 'data[localizable]', $this->model->getLocalizable());
$vs = $this->model->getInputSetting(array());
$f->addSelectField(array('1'=>$this->locale->_('yes'), '0'=>$this->locale->_('no')),
    $this->locale->_('enable'), 'enabled', 'data[enabled]', $this->model->getEnabled());
$f->endGroup();

$f->beginGroup('tab_input', $this->locale->_('attrs_input_setting'));
$f->addSelectField(array('1'=>$this->locale->_('yes'), '0'=>$this->locale->_('no')),
    $this->locale->_('attrs_required'), 'required', 'data[required]', $this->model->getEnabled());
switch($this->model->getInputType()):
    case \Core\Attrs\Model\AttributeModel::INPUT_TYPE_TEXTBOX:
        switch($this->model->getDataType()):
            case \Core\Attrs\Model\AttributeModel::DATA_TYPE_INTEGER:
                $f->addInputField('text', $this->locale->_('attrs_min_value'), 'min_value', 'data[input_setting][min_value]',
                    array_key_exists('min_value', $vs)? $vs['min_value']: '')
                    ->addValidateRule('integer', true);
                $f->addInputField('text', $this->locale->_('attrs_max_value'), 'max_value', 'data[input_setting][max_value]',
                    array_key_exists('max_value', $vs)? $vs['max_value']: '')
                    ->addValidateRule('integer', true)->addValidateRule('greatto', '#min_value', $this->locale->_('attrs_max_great_min'));
                break;
            case \Core\Attrs\Model\AttributeModel::DATA_TYPE_NUMBER:
                $f->addInputField('text', $this->locale->_('attrs_min_value'), 'min_value', 'data[input_setting][min_value]',
                    array_key_exists('min_value', $vs)? $vs['min_value']: '')
                    ->addValidateRule('number', true);
                $f->addInputField('text', $this->locale->_('attrs_max_value'), 'max_value', 'data[input_setting][max_value]',
                    array_key_exists('max_value', $vs)? $vs['max_value']: '')
                    ->addValidateRule('number', true)->addValidateRule('greatto', '#min_value', $this->locale->_('attrs_max_great_min'));
                break;
            case \Core\Attrs\Model\AttributeModel::DATA_TYPE_STRING:
                $f->addInputField('text', $this->locale->_('attrs_max_length'), 'max_length', 'data[input_setting][max_length]',
                    array_key_exists('max_length', $vs)? $vs['max_length']: false)
                    ->addValidateRule('integer', true);
                break;
        endswitch;
        break;
    case \Core\Attrs\Model\AttributeModel::INPUT_TYPE_SELECT:
    case \Core\Attrs\Model\AttributeModel::INPUT_TYPE_OPTION_LIST:
        $f->addSelectField(array('1'=>$this->locale->_('yes'), '0'=>$this->locale->_('no')),
            $this->locale->_('attrs_multiple'), 'multiple', 'data[input_setting][multiple]',
            array_key_exists('multiple', $vs)? $vs['multiple']: false)
            ->addValidateRule('required', true);
        if($this->model->getInputType() == \Core\Attrs\Model\AttributeModel::INPUT_TYPE_SELECT):
            $f->addInputField('text', $this->locale->_('attrs_multiple_size'), 'size', 'data[input_setting][size]',
                array_key_exists('size', $vs)? $vs['size']: 1)
                ->addValidateRule('required', true)
                ->addValidateRule('integer', true);
            $f->addSelectField(array('1'=>$this->locale->_('attrs_empty_option'), '2'=>$this->locale->_('attrs_first_option')),
                $this->locale->_('attrs_default_option'), 'default_option', 'data[input_setting][default_option]',
                array_key_exists('default_option', $vs)? $vs['default_option']: false)
                ->addValidateRule('required', true);
        endif;
        break;
endswitch;
$f->endGroup();

$langs = $this->locale->getLanguages();
switch($this->model->getInputType()):
    case \Core\Attrs\Model\AttributeModel::INPUT_TYPE_SELECT:
    case \Core\Attrs\Model\AttributeModel::INPUT_TYPE_OPTION_LIST:
        $f->beginGroup('tab_option', $this->locale->_('attrs_option'));
        $options = $this->model->getOptions(array());
        $f->addCustomField(function() use($options, $langId, $langs){
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
    $f->addInputField('text', $this->locale->_('text'), 'display_label_'.$lang['id'], 'data[display_text]['.$lang['id'].']',
        array_key_exists($lang['id'], $dlabels)? $dlabels[$lang['id']]: '')
        ->addValidateRule('required', true);
    $f->addInputField('text', $this->locale->_('memo'), 'memo_'.$lang['id'], 'data[memo]['.$lang['id'].']',
        array_key_exists($lang['id'], $flabels)? $flabels[$lang['id']]: '');
    $f->endGroup();
endforeach;
$f->addHiddenField('data_type', 'data[data_type]', $this->model->getDataType());
$f->addHiddenField('input_type', 'data[input_type]', $this->model->getInputType());
$f->addHiddenField('component_id', 'data[component_id]', $this->model->getComponentId());
$f->addHiddenField('next_action', 'next_action', '');
$f->addHiddenField('id', 'data[id]', $this->model->getId());

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

        $('#option_table').delegate('button', 'click', function(){
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