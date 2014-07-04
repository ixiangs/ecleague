<!DOCTYPE html>
<html slick-uniqueid="3">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $this->renderBlock('title', $this->localize->_('website_title')); ?></title>
    <link href="<?php echo CSS_URL; ?>bootstrap.css" rel="stylesheet">
    <link href="<?php echo CSS_URL; ?>font-awesome.css" rel="stylesheet">
    <link href="<?php echo CSS_URL; ?>style.css" rel="stylesheet">
    <?php echo $this->renderReferenceCss(); ?>
    <link href="<?php echo CSS_URL; ?>admin.css" rel="stylesheet">
    <script src="<?php echo JS_URL; ?>mootools.js"></script>
    <script src="<?php echo JS_URL; ?>locale.js"></script>
    <script src="<?php echo JS_URL; ?>ui.js"></script>
    <script src="<?php echo JS_URL; ?>toy/core.js"></script>
    <script src="<?php echo JS_URL; ?>toy/html.js"></script>
<!--    <script src="--><?php //echo JS_URL; ?><!--toy/locale.js"></script>-->
    <script src="<?php echo JS_URL; ?>common.js"></script>
    <?php echo $this->renderReferenceScripts(); ?>
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body class="bigscreen">
<?php echo $this->includeTemplate('topmenu'); ?>
<div class="content">
    <?php echo $this->includeTemplate('sidemenu'); ?>
    <div class="mainbar">
        <div class="page-head">
            <!-- Breadcrumb -->
            <div class="bread-crumb">
                <?php $breadcrumbs = \Toy\Web\Application::getRequestComponent('User')->getBreadcrumbs();?>
                <a href="#"> <?php echo $this->localize->_($breadcrumbs[$this->router->component]); ?></a>
                <span class="divider">/</span>
                <a href="#"> <?php echo $this->localize->_($breadcrumbs[$this->router->controller.'_'.$this->router->action]); ?></a>
            </div>

            <div class="clearfix"></div>

        </div>
        <div class="matter">
            <div class="container">
                <?php echo $this->renderBlock('content'); ?>
            </div>
        </div>
    </div>

</div>


<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <!-- Copyright info -->
                <p class="copy">Copyright © 2013 | <a href="#">Your Site</a></p>
            </div>
        </div>
    </div>
</footer>
<?php echo $this->renderScriptBlocks(); ?>
</body>
</html>