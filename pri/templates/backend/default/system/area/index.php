<?php $this->beginBlock('main'); ?>
<nav class="navbar navbar-default" role="navigation">
	<div class="navbar-header">
		<ol class="breadcrumb">
		  <li><?php echo $this->languages['system_manage']; ?></li>
		  <li class="active"><?php echo $this->languages['area_data']; ?></li>
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
<th><?php echo $this->languages['area_code']; ?></th>
<th><?php echo $this->languages['name']; ?></th>
<th><?php echo $this->languages['phone_code']; ?></th>
<th class="edit"></th>
</tr>
</thead>
<tbody>
<?php foreach($this->areas as $i=>$area): ?>
<tr>
<td class="index"><?php echo $i+1; ?></td>
<td><?php echo $area->getId(); ?></td>
<td><?php echo $area->getName(); ?></td>
<td><?php echo $area->getPhoneCode(); ?></td>
<td class="edit"><a href="<?php echo $this->router->buildUrl('edit', array('id'=>$area->getId())); ?>"><?php echo $this->languages['edit']; ?></a></td>
</tr>
<?php endforeach; ?>
</tbady>
</table>
<div class="pull-right">
<?php echo $this->includeTemplate('pagination'); ?>
</div>
<?php $this->endBlock(); ?>
<?php echo $this->includeTemplate('master'); ?>
