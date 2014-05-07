<?php
$langId = $this->locale->getLanguageId();
$this->assign('breadcrumb', array(
    $this->html->anchor($this->locale->_('attrs_manage')),
    $this->html->anchor($this->model->name[$langId]),
    $this->html->anchor($this->locale->_('layout')
)));

$this->assign('navigationBar', array(
    $this->html->anchor($this->locale->_('back'), $this->router->buildUrl('list'))
));

$this->assign('toolbar', array(
    $this->html->button('button', $this->locale->_('save'), 'btn btn-primary')->setAttribute('id', 'save')
));

$locale = $this->locale;
$f = $this->html->form();
$f->newField('')->setRenderer(function($field) use($langId){
        $aids = array();
        $res = array('<div class="form-group"><div class="col-md-6"><div class="panel panel-default">');
        $res[] = '<div class="panel-heading">'.$this->locale->_('attrs_assigned_attribute').'</div>';
        $res[] = '<div class="panel-body"><ul id="selected_attributes" class="sortable" style="min-height:40px;">';
        foreach($this->selectedAttributes as $attr){
            $res[] = '<li class="ui-state-default" data-id="'.$attr->getId().'">'.$attr->label[$langId].'</li>';
            $aids[] = $attr->getId();
        }
        $res[] = '</ul></div></div></div>';
        $res[] = '<div class="col-md-6"><div class="panel panel-default">';
        $res[] = '<div class="panel-heading">'.$this->locale->_('attrs_assignable_attribute').'</div>';
        $res[] = '<div class="panel-body"><ul id="unselected_attributes" class="sortable" style="min-height:40px;">';
        foreach($this->unselectedAttributes as $attr){
            $res[] = '<li class="ui-state-default" data-id="'.$attr->getId().'">';
            $res[] = $attr->label[$langId];
            $res[] = '</li>';
        }
        $res[] = '</ul></div></div></div></div>';
        return implode('', $res);
    });

$this->assign('form', $f);
?>
<?php $this->nextBlock('footerjs'); ?>
    <script language="javascript">
        $(document).ready(function () {
            $(".sortable").sortable({
                connectWith: '.sortable',
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
        $('#save').click(function(){
            var passed = false;
            var $f = $('#form1');
            $('#form1 input[type="hidden"]').remove();
            $('#selected_attributes li').each(function(){
                passed = true;
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