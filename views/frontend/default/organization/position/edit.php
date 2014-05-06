<?php $this->beginBlock('main'); ?>
<nav class="navbar navbar-default" role="navigation">
  <div class="navbar-header">
    <ol class="breadcrumb">
		  <li><?php echo $this->languages['organisation']; ?></li>
		  <li class="active"><?php echo $this->languages['organization_position_list']; ?></li>
    </ol>
  </div>
  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul class="nav navbar-nav navbar-right">
      <li><a href="<?php echo $this->router->buildUrl('index'); ?>"><?php echo $this->languages['back']; ?></a></li>
    </ul>
  </div>
</nav>
<?php echo $this->includeTemplate('alert'); ?>
<form role="form" id="form1" method="post">
	<?php 
		echo $this->formField('department', $this->languages['owner_department'], $this->htmlGroupSelect($this->languages['please_select'], $this->departments, 'department_id', 'department_id', 'form-control', $this->model->getDepartmentId(), array('data-validate-required'=>'true')));
		echo $this->formField('parent_id', $this->languages['boss_position'], $this->htmlSelect(array('0'=>$this->languages['none']), $this->positions, 'parent_id', 'parent_id', 'form-control', $this->model->getParentId(), array('data-validate-required'=>'true')));
	  echo $this->formField('chinese_name', $this->languages['chinese_name'], $this->htmlInput('text', 'chinese_name', 'chinese_name', 'form-control', $this->model->getChineseName(), array('data-validate-required'=>'true')));
	  echo $this->formField('english_name', $this->languages['english_name'], $this->htmlInput('text', 'english_name', 'english_name', 'form-control', $this->model->getEnglishName(), array('data-validate-required'=>'true')));
  ?>
  <button type="submit" class="btn btn-primary"><?php echo $this->languages['save']; ?></button>
  <input type="hidden" id="id" name="id" value="<?php echo $this->model->getId() ?>"/>
</form>
<?php $this->nextBlock('footerjs'); ?>
<script>
  var validator = new Toy.FormValidator('form1');
</script>
<?php $this->endBlock(); ?>
<?php echo $this->includeTemplate('master'); ?>