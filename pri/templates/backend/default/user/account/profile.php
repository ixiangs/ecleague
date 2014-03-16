<?php $this->beginBlock('main'); ?>
<nav class="navbar navbar-default" role="navigation">
  <div class="navbar-header">
    <ol class="breadcrumb">
		  <li><?php echo $this->languages['user_manage']; ?></li>
		  <li class="active"><?php echo $this->languages['user_profile']; ?></li>
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
	  echo $this->formField('chinese_name', $this->languages['chinese_name'], $this->htmlInput('text', 'chinese_name', 'chinese_name', 'form-control', $this->model->getChineseName(), array('data-validate-required'=>'true')));
	  echo $this->formField('english_name', $this->languages['english_name'], $this->htmlInput('text', 'english_name', 'english_name', 'form-control', $this->model->getEnglishName(), array('data-validate-required'=>'true')));
	  echo $this->formField('gender', $this->languages['gender'], $this->htmlSelect($this->languages['please_select'], array(''=>'', '1'=>$this->languages['male'], '2'=>$this->languages['female']), 'gender', 'gender', 'form-control', $this->model->getGender(), array('data-validate-required'=>'true')));
	  echo $this->formField('personal_email', $this->languages['personal_email'], $this->htmlInput('text', 'personal_email', 'personal_email', 'form-control', $this->model->getPersonalEmail(), array('data-validate-email'=>'TRUE')));
	  echo $this->formField('personal_phone', $this->languages['personal_phone'], $this->htmlInput('text', 'personal_phone', 'personal_phone', 'form-control', $this->model->getPersonalPhone()));
		echo $this->formField('work_email', $this->languages['work_email'], $this->htmlInput('text', 'work_email', 'work_email', 'form-control', $this->model->getWorkEmail(), array('data-validate-email'=>'TRUE')));
		echo $this->formField('work_phone', $this->languages['work_phone'], $this->htmlInput('text', 'work_phone', 'work_phone', 'form-control', $this->model->getWorkPhone()));
	  echo $this->formField('mobile', $this->languages['mobile'], $this->htmlInput('text', 'mobile', 'mobile', 'form-control', $this->model->getMobile()));
	  // echo $this->formField('address', $this->languages['address'], $this->htmlInput('text', 'address', 'address', 'form-control', $this->model->getAddress()));
  ?>
  <button type="submit" class="btn btn-primary"><?php echo $this->languages['save']; ?></button>
  <input type="hidden" id="account_id" name="account_id" value="<?php echo $this->model->getAccountId() ?>"/>
</form>
<?php $this->nextBlock('footerjs'); ?>
<script>
  var validator = new Toy.FormValidator('form1');
</script>
<?php $this->endBlock(); ?>
<?php echo $this->includeTemplate('master'); ?>