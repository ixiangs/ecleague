<?php $this->beginBlock('content');
$langId = $this->locale->getCurrentLanguageId();
?>
<div class="row breadcrumb-row">
<ol class="breadcrumb col-md-6">
<li><?php echo $this->locale->_('attrs_manage'); ?></li>
<li class="active"><?php echo $this->locale->_('attrs_attribute_set'); ?></li>
</ol>
<div class="pull-right">
<?php
echo $this->html->anchor($this->locale->_('attrs_add_group'), $this->router->buildUrl('add'))
        ->setAttribute('class', 'btn btn-default')
        ->render();
?>
</div>
</div>
<form id="table_form" method="post">
<div class="row">
<div class="panel panel-default">
<div class="panel-heading  text-right">
<?php echo $this->html->button('button', $this->locale->_('save'), 'btn btn-primary')->setAttribute('data-submit', 'form1')->render(); ?>
</div>
<div class="panel-body">
<div class="col-md-8">
<?php foreach($this->groups as $group):?>
<div class="panel panel-default">
<div class="panel-heading">
<?php echo $group->name[$langId] ?>
</div>
<div class="panel-body">
<div class="dd" id="group_<?php echo $group->getId(); ?>">
<ol class="dd-list">
<?php foreach($group->getAttributes() as $attr):?>
<li class="dd-item" data-id="<?php echo $attr->getId(); ?>">
<div class="dd-handle"><?php echo $attr->display_text[$langId]; ?></div>
</li>
<?php endforeach; ?>
</ol>
</div>
</div>
</div>
<?php endforeach; ?>
</div>
<div class="col-md-4">
<div class="panel panel-default">
<div class="panel-heading">
<?php echo $this->locale->_('attrs_attribute_list');?>
</div>
<div class="panel-body">
<div class="dd" id="unselect">
<ol class="dd-list">
<?php foreach($this->attributes as $attr):?>
<li class="dd-item" data-id="<?php echo $attr->getId(); ?>">
    <div class="dd-handle"><?php echo $attr->display_text[$langId]; ?></div>
</li>
<?php endforeach; ?>
</ol>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</form>
<?php
$this->endBlock();
$this->nextBlock('headcss');
echo '<link href="/pub/assets/css/nestable.css" rel="stylesheet">';
$this->nextBlock('headjs');
echo '<script src="/pub/assets/js/jquery.nestable.js"></script>';
$this->nextBlock('footerjs');
?>
    <script language="javascript">
        $(document).ready(function () {
            $('.dd').nestable({group: 1, maxDepth:1});
        });

//        function saveSort(){
//            $('#data').val(JSON.encode($('#nestable').nestable('serialize')));
//            $('#table_form').submit();
//        }
    </script>
<?php
$this->endBlock();
echo $this->includeTemplate('layout\base');