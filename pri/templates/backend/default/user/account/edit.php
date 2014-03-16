<?php $this->beginBlock('main'); ?>
<nav class="navbar navbar-default" role="navigation">
  <div class="navbar-header">
    <ol class="breadcrumb">
      <li><?php echo $this->languages['user_manage']; ?></li>
      <li><?php echo $this->languages['account_list']; ?></li>
    </ol>
  </div>
  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul class="nav navbar-nav navbar-right">
      <li><a href="<?php echo $this->router->buildUrl('index'); ?>"><?php echo $this->languages['back']; ?></a></li>
    </ul>
  </div>
</nav>
<?php echo $this->includeTemplate('alert'); ?>
<form class="form-horizontal" role="form" id="form1" method="post">
  <div class="form-group">
    <label for="username" class="col-sm-2 control-label"><?php echo $this->languages['username']; ?></label>
    <div class="col-sm-10">
    	<?php if($this->model->getId() == 0): ?>
    		<input type="text" value="<?php echo $this->model->getUsername(); ?>" id="username" name="username" class="form-control" data-validate-minlength="6", data-validate-maxlength="50" data-validate-required="true" data-validate-regexp="^[a-zA-Z0-9\-_\.@]+$"/>
    	<?php else: ?>
    	<input type="text" value="<?php echo $this->model->getUsername(); ?>" id="username" name="username" class="form-control" readonly="true"/>
    	<?php endif; ?>
    </div>
  </div>  
  <?php if($this->model->getId() == 0):?>
  <div class="form-group">
    <label for="password" class="col-sm-2 control-label"><?php echo $this->languages['password']; ?></label>
    <div class="col-sm-10">
    	<input type="text" id="password" name="password" class="form-control" data-validate-required="true" data-validate-minlength="6"/> 
    </div>
  </div> 
  <?php endif; ?>
  <div class="form-group">
    <label for="email" class="col-sm-2 control-label"><?php echo $this->languages['email']; ?></label>
    <div class="col-sm-10">
    	<input type="text" value="<?php echo $this->model->getEmail(); ?>" id="email" name="email" class="form-control" data-validate-required="true" data-validate-email="true"/>
    </div>
  </div> 
  <div class="form-group">
    <label for="email" class="col-sm-2 control-label"><?php echo $this->languages['role_list']; ?></label>
    <div class="col-sm-10">
    	<?php 
    	echo $this->htmlCheckboxes($this->roles, 'role_ids[]', 'form-control', $this->model->getRoleIds(array()));?>
    </div>
  </div>    
  <div class="form-group">
    <label for="level" class="col-sm-2 control-label"><?php echo $this->languages['level']; ?></label>
    <div class="col-sm-10">
    	<?php 
    		echo $this->htmlSelect(null, array(
				\User\AccountModel::LEVEL_NORMAL=>$this->languages['account_level_normal'],
				\User\AccountModel::LEVEL_ADMINISTRATOR=>$this->languages['account_level_admin']
				), 'level', 'level', 'form-control', $this->model->getLevel()); 
			?>
    </div>
  </div>
  <div class="form-group">
    <label for="status" class="col-sm-2 control-label"><?php echo $this->languages['status']; ?></label>
    <div class="col-sm-10">
    	<?php 
    		echo $this->htmlSelect(null, array(
				\User\AccountModel::STATUS_ACTIVATED=>$this->languages['account_status_activated'],
				\User\AccountModel::STATUS_NONACTIVATED=>$this->languages['account_status_nonactivated'],
				\User\AccountModel::STATUS_DISABLED=>$this->languages['account_status_disabled'],
				), 'status', 'status', 'form-control', $this->model->getStatus());
			?>
    </div>
  </div>           
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-primary"><?php echo $this->languages['save']; ?></button>
    </div>
  </div>
  <input type="hidden" id="id" name="id" value="<?php echo $this->model->getId() ?>"/>
</form>
<?php $this->nextBlock('footerjs'); ?>
<script>
  var validator = new Toy.FormValidator('form1');
</script>
<?php $this->endBlock(); ?>
<?php echo $this->includeTemplate('master'); ?>