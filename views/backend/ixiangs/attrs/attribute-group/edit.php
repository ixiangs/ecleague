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
$langId = $this->locale->getLanguageId();
$unselectedAttributes = $this->unselectedAttributes;
$selectedAttributes = $this->selectedAttributes;

$f = $this->html->groupedForm()
    ->setAttribute('action', $this->router->buildUrl('save', '*'));
$f->beginGroup('tab_base', $this->locale->_('base_info'));
if($this->model->id):
    $f->addStaticField($this->locale->_('attrs_owner_component'), $this->components[$this->model->getComponentId()]);
else:
    $f->newField($this->locale->_('attrs_owner_component'), true,
        $this->html->select('component_id', 'component_id', $this->request->getQuery('component_id'), $this->components)
            ->setCaption('')
            ->addValidateRule('required', true));
endif;
$f->newField($this->locale->_('enable'), true, $this->html->select(
    'enabled', 'data[enabled]', $this->model->getEnabled(), array('1'=>$this->locale->_('yes'), '0'=>$this->locale->_('no'))));
$f->endGroup();

foreach($this->locale->getAllLanguages() as $lang):
    $f->beginGroup('tab_lang_'.$lang['code'], $lang['name']);
    $f->newField($this->locale->_('name'), true,
        $this->html->textbox('name_'.$lang['id'], 'data[name]['.$lang['id'].']', $this->model->name[$lang['id']])
        ->addValidateRule('required', true));
    $f->newField($this->locale->_('memo'), false,
        $this->html->textbox('memo_'.$lang['id'], 'data[memo]['.$lang['id'].']', $this->model->name[$lang['id']]));
    $f->endGroup();
endforeach;

$f->addHidden('id', 'data[id]', $this->model->getId());
$f->addHidden('component_id', 'data[component_id]', $this->model->getComponentId());
$f->addHidden('set_id', 'data[set_id]', $this->model->getSetId());
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