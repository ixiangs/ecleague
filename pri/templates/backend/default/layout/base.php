<!DOCTYPE html>
<html>
<head>
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $this->renderBlock('title', $this->locale->_('website_title')); ?></title>

    <link href="/pub/assets/css/font-awesome.css" rel="stylesheet">
    <link href="/pub/assets/css/bootstrap.css" rel="stylesheet">
<!--    <link href="/pub/assets/css/bootstrap-responsive.min.css" rel="stylesheet">-->

<!--    <link href="/pub/assets/css/jquery-ui.css" rel="stylesheet">-->
    <link href="/pub/assets/css/base-admin.css" rel="stylesheet">
    <link href="/pub/assets/css/base-admin-responsive.css" rel="stylesheet">
    <link href="/pub/assets/css/admin.css" rel="stylesheet">

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
<?php echo $this->includeTemplate('topmenu'); ?>
<?php echo $this->includeTemplate('navmenu'); ?>

<div class="main">
    <div class="container">
        <?php echo $this->renderBlock('content'); ?>
    </div>
</div>
<div class="footer">

    <div class="container">

        <div class="row">

            <div id="footer-copyright" class="col-md-6">

            </div> <!-- /span6 -->

            <div id="footer-terms" class="col-md-6">

            </div> <!-- /.span6 -->

        </div> <!-- /row -->

    </div> <!-- /container -->

</div>
<?php echo $this->renderBlock('footerjs'); ?>
</body>
</html>