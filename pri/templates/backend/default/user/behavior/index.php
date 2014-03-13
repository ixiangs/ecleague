<?php $this->beginBlock('main'); ?>
<nav class="navbar navbar-default" role="navigation">
	<div class="navbar-header">
		<ol class="breadcrumb">
		  <li><?php echo $this->languages['user_manage']; ?></li>
		  <li class="active"><?php echo $this->languages['behavior_list']; ?></li>
		</ol>
  </div>
  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul class="nav navbar-nav navbar-right">
      <li><a href="<?php echo $this->router->buildUrl('add'); ?>"><?php echo $this->languages['add']; ?></a></li>
    </ul>
  </div>
</nav>
<table class="table table-striped table-hover table-bordered">
<thead>
<tr>
<th class="index">#</th>
<th><?php echo $this->languages['code']; ?></th>
<th><?php echo $this->languages['label']; ?></th>
<th><?php echo $this->languages['enable']; ?></th>
<th class="edit"></th>
</tr>
</thead>
<tbody>
<?php foreach($this->models as $i=>$model): ?>
<tr>
<td class="index"><?php echo $i+1; ?></td>
<td><?php echo $model->getCode(); ?></td>
<td><?php echo $model->getLabel(); ?></td>
<td>
<?php 
if($model->getEnabled()):
	echo $this->languages['yes'];
else:
	echo $this->languages['no'];
endif;
?>
</td>
<td class="edit">
	<a href="<?php echo $this->router->buildUrl('edit', array('id'=>$model->getId())); ?>"><?php echo $this->languages['edit']; ?></a>
	<a href="<?php echo $this->deleteConfirm($this->router->buildUrl('delete', array('id'=>$model->getId()))); ?>"><?php echo $this->languages['delete']; ?></a>	
</td>
</tr>
<?php endforeach; ?>
</tbady>
</table>
<div class="pull-right">
<?php echo $this->includeTemplate('pagination'); ?>
</div>
<?php $this->endBlock(); ?>
<?php echo $this->includeTemplate('master'); ?>
