<?php $this->beginBlock('main'); ?>
<nav class="navbar navbar-default" role="navigation">
  <div class="navbar-header">
    <ol class="breadcrumb">
      <li><?php echo $this->languages['system_manage']; ?></li>
      <li><?php echo $this->languages['area_data']; ?></li>
    </ol>
  </div>
  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul class="nav navbar-nav navbar-right">
      <li><a href="<?php echo $this->history->find($this->router->buildUrl('index')); ?>"><?php echo $this->languages['back']; ?></a></li>
    </ul>
  </div>
</nav>
<?php
echo $this->includeTemplate('alert'); 
$form = $this->html->createForm();
echo $form->renderBegin();
echo $form->renderInput(
	'text', $this->languages['area_code'], 'id', 'id', 
	$this->model->getId(), 
	array(
		'data-validate-required'=>"true", 
		'data-validate-integer'=>"true")
);
echo $form->renderInput(
	'text', $this->languages['area_name'], 'name', 'name', 
	$this->model->getName(), 
	array('data-validate-required'=>"true")
);
?>
<button type="submit" class="btn btn-primary"><?php echo $this->languages['save']; ?></button>
<?php
echo $form->renderEnd();
?>
<?php $this->nextBlock('footerjs'); ?>
<script>
  var validator = new Toys.FormValidator('form1');
</script>
<?php $this->endBlock(); ?>
<?php echo $this->includeTemplate('master'); ?>