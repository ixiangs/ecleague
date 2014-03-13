<?php $this->beginBlock('main'); ?>
<nav class="navbar navbar-default" role="navigation">
	<div class="navbar-header">
		<ol class="breadcrumb">
		  <li><?php echo $this->languages['user_manage']; ?></li>
		  <li class="active"><?php echo $this->languages['account_list']; ?></li>
		</ol>
  </div>
  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul class="nav navbar-nav navbar-right">
    	<li><a href="<?php echo $this->router->buildUrl('export-contacts'); ?>"><?php echo $this->languages['export_contacts']; ?></a></li>
      <li><a href="<?php echo $this->router->buildUrl('add'); ?>"><?php echo $this->languages['add']; ?></a></li>
    </ul>
  </div>
</nav>
<table class="table table-striped table-hover table-bordered">
<thead>
<tr>
<th class="index">#</th>
<th><?php echo $this->languages['username']; ?></th>
<th><?php echo $this->languages['email']; ?></th>
<th><?php echo $this->languages['role_list']; ?></th>
<th><?php echo $this->languages['level']; ?></th>
<th><?php echo $this->languages['status']; ?></th>
<th class="edit"></th>
</tr>
</thead>
<tbody>
<?php foreach($this->models as $i=>$model): ?>
<tr>
<td class="index"><?php echo $i+1; ?></td>
<td><?php echo $model->getUsername(); ?></td>
<td><?php echo $model->getEmail(); ?></td>
<td>
<?php
$codes = array();
foreach($this->roles as $bid=>$code):
	if(in_array($bid, $model->getRoleIds(array()))):
		$codes[] = $code;
	endif;
endforeach;
echo implode(', ', $codes);
?>
</td>
<td>
<?php 
switch($model->getLevel()):
	case \User\AccountModel::LEVEL_ADMINISTRATOR:
		echo $this->languages['account_level_admin'];
		break;
	case \User\AccountModel::LEVEL_NORMAL:
		echo $this->languages['account_level_normal'];
		break;
endswitch;
?>
</td>
<td>
<?php 
switch($model->getStatus()):
	case \User\AccountModel::STATUS_ACTIVATED:
		echo $this->languages['account_status_activated'];
		break;
	case \User\AccountModel::STATUS_NONACTIVATED:
		echo $this->languages['account_status_nonactivated'];
		break;
	case \User\AccountModel::STATUS_DISABLED:
		echo $this->languages['account_status_freezed'];
		break;		
endswitch;
?>
</td>
<td class="edit">
	<a href="<?php echo $this->router->buildUrl('profile', array('id'=>$model->getId())); ?>"><?php echo $this->languages['user_profile']; ?></a>
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
