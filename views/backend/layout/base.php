<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $this->renderBlock('title', $this->locale->_('website_title')); ?></title>

    <link href="<?php echo CSS_URL; ?>bootstrap.css" rel="stylesheet">
    <link href="<?php echo CSS_URL; ?>bootstrap-theme.css" rel="stylesheet">
    <link href="<?php echo CSS_URL; ?>font-awesome.css" rel="stylesheet">
    <link href="<?php echo CSS_URL; ?>base-admin.css" rel="stylesheet">
    <link href="<?php echo CSS_URL; ?>base-admin-responsive.css" rel="stylesheet">
    <?php echo $this->renderReferenceCss(); ?>
    <link href="<?php echo CSS_URL; ?>admin.css" rel="stylesheet">

    <script src="<?php echo JS_URL; ?>libs.js"></script>
    <script src="<?php echo JS_URL; ?>datepicker/bootstrap-datepicker.js"></script>
    <script src="<?php echo JS_URL; ?>datepicker/locales/bootstrap-datepicker.zh-CN.js"></script>
    <script src="<?php echo JS_URL; ?>toy/core.js"></script>
    <script src="<?php echo JS_URL; ?>toy/html.js"></script>
    <script src="<?php echo JS_URL; ?>toy/locale.js"></script>
    <script src="<?php echo JS_URL; ?>common.js"></script>
    <?php echo $this->renderReferenceScripts(); ?>
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body>
<?php echo $this->includeTemplate('topmenu'); ?>
<?php echo $this->includeTemplate('ixiangs/system/menu/nav'); ?>

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
<?php echo $this->renderScriptBlocks(); ?>
</body>
</html>