<?php
$this->assign('breadcrumb', array(
    $this->html->anchor($this->locale->_('attrs_manage')),
    $this->html->anchor($this->locale->_($this->router->action == 'add'?'attrs_new_group':'attrs_edit_group'))
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
    $this->html->button('button', $this->locale->_('save'), 'btn btn-primary')
        ->setAttribute('data-submit', 'form1')
));

$locale = $this->locale;
$langId = $this->locale->getCurrentLanguageId();
$unselectedAttributes = $this->unselectedAttributes;
$selectedAttributes = $this->selectedAttributes;

$f = $this->html->groupedForm()
    ->setAttribute('action', $this->router->buildUrl('save', '*'));
$f->beginGroup('tab_base', $this->locale->_('base_info'));
$f->addSelectField(array('1'=>$this->locale->_('yes'), '0'=>$this->locale->_('no')),
    $this->locale->_('enable'), 'enabled', 'data[enabled]', $this->model->getEnabled());
$f->addInputField('text', $this->locale->_('attrs_attribute'), 'attributes', 'attributes')
    ->getInput()->setRenderer(function($field) use($unselectedAttributes, $selectedAttributes, $locale, $langId){
        $aids = array();
        $res = array('<div class="panel panel-default panel-thin">');
        $res[] = '<div class="panel-heading">'.$locale->_('attrs_assigned_attribute').'</div>';
        $res[] = '<div class="panel-body"><ul id="selected_attributes" class="label-list" style="min-height:40px;">';
        foreach($selectedAttributes as $attr){
            $res[] = '<li class="ui-state-default" data-id="'.$attr->getId().'"><span class="label label-primary">'.$attr->display_text[$langId].'</span></li>';
            $aids[] = $attr->getId();
        }
        $res[] = '</ul></div></div>';
        $res[] = '<div class="panel panel-default panel-thin">';
        $res[] = '<div class="panel-heading">'.$locale->_('attrs_assignable_attribute').'</div>';
        $res[] = '<div class="panel-body"><ul id="unselected_attributes" class="label-list" style="min-height:40px;">';
        foreach($unselectedAttributes as $attr){
            $res[] = '<li class="ui-state-default col-md-2" data-id="'.$attr->getId().'"><span class="label label-primary col-md-12">'.$attr->display_text[$langId].'</span></li>';
        }
        $res[] = '</ul></div></div>';
        $res[] = '<input type="hidden" id="attribute_ids" name="attribute_ids" value="'.implode(',', $aids).'" data-validate-required="true"/>';
        return implode('', $res);
    });
$f->endGroup();

foreach($this->locale->getLanguages() as $lang):
    $f->beginGroup('tab_lang_'.$lang['code'], $lang['name']);
    $f->addInputField('text', $this->locale->_('name'), 'name_'.$lang['id'], 'data[name]['.$lang['id'].']', $this->model->name[$lang['id']])
        ->addValidateRule('required', true);
    $f->addInputField('text', $this->locale->_('memo'), 'memo_'.$lang['id'], 'data[memo]['.$lang['id'].']', $this->model->name[$lang['id']]);
    $f->endGroup();
endforeach;

$f->addHiddenField('id', 'data[id]', $this->model->getId());
$f->addHiddenField('component_id', 'data[component_id]', $this->model->getComponentId());
$f->addHiddenField('set_id', 'data[set_id]', $this->model->getSetId());
$this->assign('form', $f);
?>
<?php $this->nextBlock('footerjs'); ?>
    <script language="javascript">
        $(document).ready(function () {
            $(".label-list").sortable({
                connectWith: '.label-list',
                dropOnEmpty: true,
                stop:function(event, ui){
                    if(ui.item.parent().attr('id') == 'selected_attributes'){
                        var ids = [];
                        ui.item.parent().children().each(function(){
                            ids.push($(this).attr('data-id'));
                        });
                        $('#attribute_ids').val(ids.join(','));
                    }
                }
            });
        });
<!--        $('#save').click(function(){-->
<!--            var passed = true;-->
<!--            var $f = $('#form1');-->
<!--            $('#form1 input[type="hidden"]').remove();-->
<!--            var selected = false;-->
<!--            $('#selected_attributes li').each(function(){-->
<!--                selected = true;-->
<!--                $f.append('<input type="hidden" name="attribute_ids[]" value="' + $(this).attr('data-id') + '"/>');-->
<!--            });-->
<!---->
<!--            if(!passed){-->
<!--                alert('--><?php //echo $this->locale->_('attrs_err_group_not_empty'); ?><!--');-->
<!--            }else{-->
<!--                $f.submit();-->
<!--            }-->
<!--        });-->
        $('#select_ok').click(function(){

        })
    </script>
<?php
$this->endBlock();
echo $this->includeTemplate('layout\form');