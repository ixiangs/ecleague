<?php
$this->assign('toolbar', array(
    $this->html->anchor($this->localize->_('new'), $this->router->buildUrl('type'))
));

$dt = $this->html->grid($this->models);
$dt->addLabelColumn($this->localize->_('title'), '@{title}', '', 'left')
    ->setCellRenderer(function($cell, $row, $index){
        return '<td>'.str_repeat('-', $row['level']).' '.$row['title'].'</td>';
    });
$dt->addTextboxColumn($this->localize->_('website_sort'),
    array('value'=>'@{ordering}', 'name'=>'@orderings[{id}]', 'maxlength'=>2, 'class'=>'ordering', 'style'=>'width:60px;'),
    array('required'=>'true', 'integer'=>'true'),
    'small', 'left')
    ->setHeadRenderer(function($cell){
        return '<th class="small"><div class="pull-left">'.$this->localize->_('website_sort').'</div>'
                .'<div class="pull-right">'
                .'<a href="javascript:saveOrdering()">'
                .'<span class="glyphicon glyphicon-floppy-disk"></span>'
                .'</a></div></th>';
    });;
$dt->addStatusColumn($this->localize->_('website_menu_type'), '@{type_id}', $this->types, 'middle', 'left');
$dt->addLinkColumn('', $this->localize->_('edit'), '@'.urldecode($this->router->buildUrl('edit', array('id'=>'{id}'))), 'edit', 'edit');
$dt->addButtonColumn('', $this->localize->_('delete'), "@deleteConfirm('".urldecode($this->router->buildUrl('delete', array('id'=>'{id}')))."')", 'edit', 'edit');
$this->assign('datatable', $dt);

$this->beginScript('menu_list');
?>
<script language="javascript">
    function saveOrdering(){
        var result = Toy.Validation.test($$('input.ordering'));
        if(result !== true){
            alert(result[0].message);
            result[0].element.select()
            result[0].element.focus();
            return;
        }

        $('table_form').set('action', '<?php echo $this->router->buildUrl('ordering');?>').submit();
    }
</script>
<?php
$this->endScript();
echo $this->includeTemplate('layout\list');