<?php $this->beginBlock('css'); ?>
<link href="/pub/assets/css/admin.css" rel="stylesheet">
<?php $this->endBlock();?>
<?php $this->beginBlock('body'); ?>
<div id="wrap">
  <div class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="sr-only"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#"><?php echo $this->languages['website_title'] ?></a>
      </div>
      <div class="collapse navbar-collapse">
        <ul class="nav navbar-nav">  
          <li class="dropdown" data-behavior="BS.Dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $this->languages['organisation'] ?> <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="<?php echo $this->router->buildUrl('organization/company/index'); ?>"><?php echo $this->languages['company_list'] ?></a></li>
              <li><a href="<?php echo $this->router->buildUrl('organization/department/index'); ?>"><?php echo $this->languages['department_list'] ?></a></li>
              <li><a href="<?php echo $this->router->buildUrl('organization/position/index'); ?>"><?php echo $this->languages['organization_position_list'] ?></a></li>
              <li><a href="<?php echo $this->router->buildUrl('organization/employee/index'); ?>"><?php echo $this->languages['employee_list'] ?></a></li>
            </ul>
          </li>                  
        </ul>      	
				<ul class="nav navbar-nav navbar-right">
					<li class="active"><a><?php echo $this->onlineAccount->getUsername(); ?></a></li>
					<li><a href="<?php echo $this->router->buildUrl('user/account/change-password') ?>"><?php echo $this->languages['change_password'] ?></a></li>
          <li><a href="<?php echo $this->router->buildUrl('user/defend/logout') ?>"><?php echo $this->languages['logout'] ?></a></li>
        </ul>        
      </div><!--/.nav-collapse -->
    </div>
  </div>

  <div class="container">
    <?php echo $this->renderBlock('main'); ?>
  </div>
</div>

<div id="footer">
  <div class="container">
    <p class="text-muted"></p>
  </div>
</div>
<?php $this->endBlock(); ?>
<?php echo $this->includeTemplate('layouts/base');
