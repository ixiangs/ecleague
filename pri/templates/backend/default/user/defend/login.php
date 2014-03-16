<?php $this->beginBlock('body'); ?>
<div class="container">
	<?php echo $this->includeTemplate('alert'); ?>
	<h2 class="form-signin-heading"><?php echo $this->languages['website_title']; ?></h2>
<form class="form-horizontal" role="form" id="form1" method="post">
  <div class="form-group">
    <label for="username" class="col-sm-2 control-label"><?php echo $this->languages['username']; ?></label>
    <div class="col-sm-10">
    	<input type="text" id="username" name="username" class="form-control" placeholder="<?php echo $this->languages['username']; ?>" data-validate-required="true" data-validate-minlength="6"/> 
    </div>
  </div> 
  <div class="form-group">
    <label for="password" class="col-sm-2 control-label"><?php echo $this->languages['password']; ?></label>
    <div class="col-sm-10">
    	<input type="password" id="password" name="password" class="form-control" placeholder="<?php echo $this->languages['password']; ?>" data-validate-required="true" data-validate-minlength="6"/> 
    </div>
  </div> 
  <button class="btn btn-lg btn-primary btn-block" type="submit"><?php echo $this->languages['login']; ?></button>
  </form>
</div>
<?php 
$this->nextBlock('footerjs'); ?>
<script>
  var validator = new Toy.FormValidator('form1');
</script>
<?php $this->endBlock();
echo $this->includeTemplate('layouts/base');