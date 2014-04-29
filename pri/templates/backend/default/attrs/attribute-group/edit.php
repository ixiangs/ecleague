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
        ->setAttribute('id', 'save')
));

$f = $this->html->groupedForm()
    ->setAttribute('action', $this->router->buildUrl('save', '*'));
$f->beginGroup('tab_base', $this->locale->_('base_info'));
$f->addSelectField(array('1'=>$this->locale->_('yes'), '0'=>$this->locale->_('no')),
    $this->locale->_('enable'), 'enabled', 'data[enabled]', $this->model->getEnabled());
//$f->addInputField('text', $this->locale->_('attrs_attribute'), 'attributes', 'attributes')
//        ->getInput()->setRenderer(function(){
//            $res = $this->html->button('button', $this->locale->_('select'))
//                        ->setAttribute(array('data-toggle'=>"modal", 'data-target'=>"#myModal"))
//                        ->render();
//            $res .= '<span class="label label-default">Default</span><span class="label label-primary">Primary</span>';
//            return $res;
//        });
//$f->addInputField('text', $this->locale->_('attrs_attribute'), 'attributes', 'attributes')
//    ->getInput()->setRenderer(function(){
//        $res = $this->html->button('button', $this->locale->_('select'))
//            ->setAttribute(array('data-toggle'=>"modal", 'data-target'=>"#myModal"))
//            ->render();
//        $res .= '<span class="label label-default">Default</span><span class="label label-primary">Primary</span>';
//        return $res;
//    });
//        ->addValidateRule('required', true)
//        ->getInput()
//            ->setRightAddon($this->html->button('button', $this->locale->_('select'))
//                ->setAttribute(array('data-toggle'=>"modal", 'data-target'=>"#myModal")))
//            ->getInput()->setAttribute('readonly', 'readonly');
$f->endGroup();

foreach($this->locale->getLanguages() as $lang):
    $f->beginGroup('tab_lang_'.$lang['code'], $lang['name']);
    $f->addInputField('text', $this->locale->_('name'), 'name_'.$lang['id'], 'data[name]['.$lang['id'].']', $this->model->name[$lang['id']])
        ->addValidateRule('required', true);
    $f->addInputField('text', $this->locale->_('memo'), 'memo_'.$lang['id'], 'data[memo]['.$lang['id'].']', $this->model->name[$lang['id']]);
    $f->endGroup();
endforeach;

$f->addHiddenField('id', 'id', $this->model->getId());
$f->addHiddenField('component_id', 'data[component_id]', $this->model->getComponentId());
$f->addHiddenField('set_id', 'data[set_id]', $this->model->getSetId());
$this->assign('form', $f);
$this->nextBlock('others');
$langId = $this->locale->getCurrentLanguageId();
$unselectedAttributes = $this->unselectedAttributes;
$selectedAttributes = $this->selectedAttributes;
?>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg">
<div class="modal-content">
<div class="modal-body clearfix">
<?php
$res = '<div class="col-md-6"><div class="panel panel-default"><div class="panel-heading">'
    . $this->locale->_('attrs_selected_attribute')
    . '</div><div class="panel-body" style="height:250px;overflow-y:auto;"><ul id="selected_attributes" class="sortable">';
foreach($selectedAttributes as $attr){
    $res .= '<li class="ui-state-default" data-name="'.$attr->display_text[$langId].'" data-id="'.$attr->getId().'">'
        . $attr->display_text[$langId].'('.$attr->memo[$langId].')'
        . '</li>';
}
$res .= '</ul></div></div></div>'
    . '<div class="col-md-6"><div class="panel panel-default"><div class="panel-heading">'
    . $this->locale->_('attrs_attribute_list')
    . '</div><div class="panel-body" style="height:250px;overflow-y:auto;"><ul id="unselected_attributes" class="sortable">';
foreach($unselectedAttributes as $attr){
    $res .= '<li class="ui-state-default" data-id="'.$attr->getId().'">'
        . $attr->display_text[$langId].'('.$attr->memo[$langId].')'
        . '</li>';
}
echo $res . '</ul></div></div></div>';
?>
</div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->locale->_('close'); ?></button>
        <button type="button" class="btn btn-primary" id="select_ok"><?php echo $this->locale->_('ok'); ?></button>
    </div>
</div>
</div>
</div>
<?php $this->nextBlock('footerjs'); ?>
    <script language="javascript">
        $(document).ready(function () {
            $(".sortable").sortable({
                connectWith: ".sortable",
                dropOnEmpty: true
            });
        });
        $('#save').click(function(){
            var passed = true;
            var $f = $('#form1');
            $('#form1 input[type="hidden"]').remove();
            var selected = false;
            $('#selected_attributes li').each(function(){
                selected = true;
                $f.append('<input type="hidden" name="attribute_ids[]" value="' + $(this).attr('data-id') + '"/>');
            });

            if(!passed){
                alert('<?php echo $this->locale->_('attrs_err_group_not_empty'); ?>');
            }else{
                $f.submit();
            }
        });
        $('#select_ok').click(function(){

        })
    </script>
<?php
$this->endBlock();
echo $this->includeTemplate('layout\form');