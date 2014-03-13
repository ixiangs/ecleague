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
      <li><a href="<?php echo $this->router->buildUrl('index'); ?>"><?php echo $this->languages['back']; ?></a></li>
    </ul>
  </div>
</nav>
<?php echo $this->includeTemplate('alert'); ?>
<form role="form" id="form1" method="post">
	<?php 
		echo $this->formField('company', $this->languages['owner_company' ], $this->htmlSelect($this->languages['please_select'], $this->companies,'company_id', 'company_id', 'form-control', $this->model->getCompanyId(), array('data-validate-required'=>'true')));
		echo $this->formField('department', $this->languages['owner_department'], $this->htmlSelect($this->languages['please_select'], $this->departments, 'department_id', 'department_id', 'form-control', $this->model->getCompanyId(), array('data-validate-required'=>'true')));
		echo $this->formField('position', 
													$this->languages['organization_position'], 
													$this->htmlSelect(
														$this->languages['please_select'], 
														$this->positions,
														'position_id', 
														'position_id', 
														'form-control', 
														$this->model->getPositionId(),
														array('data-validate-required'=>'true')));
		echo $this->formField('boss', $this->languages['boss'], $this->htmlSelect(array('0'=>$this->languages['none']), $this->employees, 'boss_id', 'boss_id', 'form-control', $this->model->getBossId(), array('data-validate-required'=>'true')));
	  echo $this->formField('chinese_name', $this->languages['chinese_name'], $this->htmlInput('text', 'chinese_name', 'chinese_name', 'form-control', $this->model->getChineseName(), array('data-validate-required'=>'true')));
	  echo $this->formField('english_name', $this->languages['english_name'], $this->htmlInput('text', 'english_name', 'english_name', 'form-control', $this->model->getEnglishName(), array('data-validate-required'=>'true')));
	  echo $this->formField('gender', $this->languages['gender'], $this->htmlSelect($this->languages['please_select'], array(''=>'', '1'=>$this->languages['male'], '2'=>$this->languages['female']), 'gender', 'gender', 'form-control', $this->model->getGender(), array('data-validate-required'=>'true')));
	  echo $this->formField('email', $this->languages['email'], $this->htmlInput('text', 'email', 'email', 'form-control', $this->model->getEmail(), array('data-validate-required'=>'true', 'data-validate-email'=>'TRUE')));
	  echo $this->formField('mobile', $this->languages['mobile'], $this->htmlInput('text', 'mobile', 'mobile', 'form-control', $this->model->getMobile(), array('data-validate-required'=>'true')));
	  echo $this->formField('phone', $this->languages['phone'], $this->htmlInput('text', 'phone', 'phone', 'form-control', $this->model->getPhone()));
	  echo $this->formField('address', $this->languages['address'], $this->htmlInput('text', 'address', 'address', 'form-control', $this->model->getAddress()));
  ?>
  <button type="submit" class="btn btn-primary"><?php echo $this->languages['save']; ?></button>
  <input type="hidden" id="id" name="id" value="<?php echo $this->model->getId() ?>"/>
</form>
<?php $this->nextBlock('footerjs'); ?>
<script>
  var validator = new Toys.FormValidator('form1');
</script>
<?php $this->endBlock(); ?>
<?php echo $this->includeTemplate('master'); ?>