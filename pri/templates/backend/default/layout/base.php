<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo $this->renderBlock('title', $this->locale->_('website_title')); ?></title>
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/pub/assets/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="/pub/assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/pub/assets/css/sb-admin.css">
    <link rel="stylesheet" type="text/css" href="/pub/assets/css/admin.css">
    <?php echo $this->renderBlock('headcss'); ?>
    <script src="/pub/assets/js/libs.js"></script>
    <script src="/pub/assets/js/bootstrap.min.js"></script>
    <script src="/pub/assets/js/toy/core.js"></script>
    <script src="/pub/assets/js/toy/html.js"></script>
    <script src="/pub/assets/js/toy/locale.js"></script>
    <script src="/pub/assets/js/common.js"></script>
    <?php echo $this->renderBlock('headjs'); ?>
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body>
<div class="contain">

    <?php echo $this->includeTemplate('sidemenu'); ?>

    <div class="right">

        <div class="col-md-12">
            <div class="logo"><img id="logo" src="pub/assets/img/logo3.png"></div>
            <div class="logoxs"><img id="logo" src="pub/assets/img/logo_sm.png"></div>
            <div class="nav">
                <div class="hov">

                </div>
            </div>
        </div>

        <!-- BEGIN PAGE CONTENT -->
        <div class="content">
            <?php echo $this->renderBlock('content'); ?>
            <!-- END PAGE CONTENT -->
        </div>
    </div>
</div>


<?php echo $this->renderBlock('footerjs'); ?>
</body>
</html>