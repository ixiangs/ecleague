<?php $this->beginBlock('main'); ?>
<nav class="navbar navbar-default" role="navigation">
	<div class="navbar-header">
		<ol class="breadcrumb">
		  <li><?php echo $this->languages['organisation']; ?></li>
		  <li class="active"><?php echo $this->languages['employee_list']; ?></li>
		</ol>
  </div>
  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul class="nav navbar-nav navbar-right">
      <li><a href="<?php echo $this->router->buildUrl('add'); ?>"><?php echo $this->languages['add']; ?></a></li>
      <li>
      	<a href="<?php echo $this->router->buildUrl('export', array('format'=>'foxmail')); ?>">
      		<?php echo $this->languages['export_to_foxmail']; ?>
      	</a>     	
      </li>
      <li>
      	<a href="<?php echo $this->router->buildUrl('export', array('format'=>'android')); ?>">
      		<?php echo $this->languages['export_to_android']; ?>
      	</a> 
      </li>
      <li>
      	<a href="<?php echo $this->router->buildUrl('export', array('format'=>'iphone')); ?>">
      		<?php echo $this->languages['export_to_iphone']; ?>
      	</a> 
      </li>         
         
    </ul>
  </div>
</nav>
<table class="table table-striped table-hover">
<thead>
<tr>
<th class="index">#</th>
<th><?php echo $this->languages['name']; ?></th>
<th><?php echo $this->languages['organization_position']; ?></th>
<th><?php echo $this->languages['email']; ?></th>
<th><?php echo $this->languages['mobile']; ?></th>
<th><?php echo $this->languages['phone']; ?></th>
<th class="edit"></th>
</tr>
</thead>
<tbody>
<?php foreach($this->models as $i=>$model): ?>
<tr>
<td class="index"><?php echo $i+1; ?></td>
<td style="text-align:center;">
	<?php echo $model->getChineseName(); ?></br>
	<?php echo $model->getEnglishName(); ?>
</td>
<td style="text-align:center">
	<?php echo $this->departments[$model->getDepartmentId()]; ?></br>
	<?php echo $this->positions[$model->getPositionId()]; ?>
</td>
<td style="text-align:right"><a href="mailto:<?php echo $model->getEmail(); ?>"><?php echo $model->getEmail(); ?></a></td>
<td style="text-align:left"><?php echo implode('</br>', explode('/', $model->getMobile())); ?></td>
<td><?php echo $model->getPhone(); ?></td>
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
