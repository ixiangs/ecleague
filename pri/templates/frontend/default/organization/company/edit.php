<?php $this->beginBlock('main'); ?>
<nav class="navbar navbar-default" role="navigation">
  <div class="navbar-header">
    <ol class="breadcrumb">
      <li><?php echo $this->languages['organisation']; ?></li>
      <li><?php echo $this->languages['company_list']; ?></li>
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
	<?php echo $this->formField('name', $this->languages['name'], $this->htmlInput('text', 'name', 'name', 'form-control', $this->model->getName(), array('data-validate-required'=>'true')));?>   
  <button type="submit" class="btn btn-primary"><?php echo $this->languages['save']; ?></button>
  <input type="hidden" id="id" name="id" value="<?php echo $this->model->getId() ?>"/>
</form>
<?php $this->nextBlock('footerjs'); ?>
<script>
  var validator = new Toys.FormValidator('form1');
</script>
<?php $this->endBlock(); ?>
<?php echo $this->includeTemplate('master'); ?>