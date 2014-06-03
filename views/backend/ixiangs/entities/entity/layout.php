<?php
$langId = $this->locale->getLanguageId();
$this->assign('breadcrumb', array(
    $this->html->anchor($this->locale->_('entities_manage')),
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
    $res[] = '<div class="panel-heading">'.$this->locale->_('entities_assigned_group').'</div>';
    $res[] = '<div class="panel-body"><ul id="assigned_group" class="sortable" style="min-height:40px;">';
    foreach($this->selectedGroups as $attr){
        $res[] = '<li class="ui-state-default" data-id="'.$attr->getId().'">'.$attr->name[$langId].'</li>';
        $aids[] = $attr->getId();
    }
    $res[] = '</ul></div></div></div>';
    $res[] = '<div class="col-md-6"><div class="panel panel-default">';
    $res[] = '<div class="panel-heading">'.$this->locale->_('entities_assignable_group').'</div>';
    $res[] = '<div class="panel-body"><ul id="assignable_attributes" class="sortable" style="min-height:40px;">';
    foreach($this->unselectedGroups as $attr){
        $res[] = '<li class="ui-state-default" data-id="'.$attr->getId().'">';
        $res[] = $attr->name[$langId];
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
                dropOnEmpty: true
            });
        });
        $('#save').click(function(){
            var passed = false;
            var $f = $('#form1');
            $('#form1 input[type="hidden"]').remove();
            $('#assigned_group li').each(function(){
                passed = true;
                $f.append('<input type="hidden" name="group_ids[]" value="' + $(this).attr('data-id') + '"/>');
            });

            if(!passed){
                alert('<?php echo $this->locale->_('entities_err_set_not_empty'); ?>');
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