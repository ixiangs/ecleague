<?php $this->beginBlock('main'); ?>
<?php echo $this->includeTemplate('alert'); ?>
	<form class="form-horizontal" role="form" id="form1" method="post">
  <div class="form-group">
    <label for="username" class="col-sm-2 control-label"><?php echo $this->languages['old_password']; ?></label>
    <div class="col-sm-10">
    	<input type="password" id="oldpassword" name="oldpassword" class="form-control" data-validate-required="true" data-validate-minlength="6"/> 
    </div>
  </div> 
  <div class="form-group">
    <label for="password" class="col-sm-2 control-label"><?php echo $this->languages['new_password']; ?></label>
    <div class="col-sm-10">
    	<input type="password" id="newpassword" name="newpassword" class="form-control" data-validate-required="true" data-validate-minlength="6"/> 
    </div>
  </div> 
  <div class="form-group">
    <label for="password" class="col-sm-2 control-label"><?php echo $this->languages['repassword']; ?></label>
    <div class="col-sm-10">
    	<input type="password" id="repassword" name="repassword" class="form-control" data-validate-required="true" data-validate-minlength="6" data-validate-equalto='newpassword'/> 
    </div>
  </div>   
  <button class="btn btn-lg btn-primary btn-block" type="submit"><?php echo $this->languages['login']; ?></button>
  </form>
<?php $this->nextBlock('footerjs'); ?>
<script>
  var validator = new Toys.FormValidator('form1');
</script>
<?php $this->endBlock();
echo $this->includeTemplate('master');