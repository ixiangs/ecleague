<?php $this->beginBlock('main'); ?>
<nav class="navbar navbar-default" role="navigation">
  <div class="navbar-header">
    <ol class="breadcrumb">
      <li><?php echo $this->languages['user_manage']; ?></li>
      <li><?php echo $this->languages['role_list']; ?></li>
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
    <label for="username" class="col-sm-2 control-label"><?php echo $this->languages['code']; ?></label>
    <div class="col-sm-10">
    	<?php
    	if($this->model->getId() == 0):
    		echo $this->htmlInput('text', 'code', 'code', 'form-control', $this->model->getCode(), array('data-validate-required'=>'true', 'data-validate-character'=>'true'));
    	else:
    		echo $this->htmlInput('text', 'code', 'code', 'form-control', $this->model->getCode(), array('readonly'=>true));
			endif;
    	?>
    </div>
  </div>  
  <div class="form-group">
    <label for="email" class="col-sm-2 control-label"><?php echo $this->languages['label']; ?></label>
    <div class="col-sm-10">
    	<?php echo $this->htmlInput('text', 'label', 'label', 'form-control', $this->model->getLabel(), array('data-validate-required'=>'true'));?>
    </div>
  </div>
  <div class="form-group">
    <label for="email" class="col-sm-2 control-label"><?php echo $this->languages['behavior_list']; ?></label>
    <div class="col-sm-10">
    	<?php 
    	echo $this->htmlCheckboxes($this->behaviors, 'behavior_ids[]', 'form-control', $this->model->getBehaviorIds(array()));?>
    </div>
  </div>   
  <div class="form-group">
    <label for="email" class="col-sm-2 control-label"><?php echo $this->languages['enable']; ?></label>
    <div class="col-sm-10">
    	<?php 
    	echo $this->htmlSelect(null, array(
				'1'=>$this->languages['yes'],
				'0'=>$this->languages['no']
			), 'enabled', 'enabled', 'form-control', $this->model->getEnabled());
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