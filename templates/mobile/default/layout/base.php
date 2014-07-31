<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $this->document->getTitle(); ?></title>
    <link href="<?php echo CSS_URL; ?>bootstrap.css" rel="stylesheet">
    <link href="<?php echo STATIC_URL; ?>mobile/<?php echo \Toy\Web\Configuration::$templateTheme ?>/style.css"
          rel="stylesheet">
    <?php echo $this->renderReferenceCss(); ?>
    <script src="<?php echo JS_URL; ?>mootools.js"></script>
    <script src="<?php echo JS_URL; ?>locale.js"></script>
    <?php echo $this->renderReferenceScripts(); ?>
    <style>
        body {
            background-color: # <?php echo $this->website->getBackgroundColor(); ?>
        }
    </style>
</head>
<body>
<?php echo $this->renderBlock('content'); ?>
<nav class="navbar-fixed-bottom">
<div class="container">
<ul class="nav nav-justified">
<?php
$menus = $this->website->getMenus()
->eq('status', \Void\Website\Constant::STATUS_MENU_ENABLE)
->asc('parent_id', 'ordering')
->load();
foreach ($menus as $menu):
if ($menu->getParentId() == 0):
?>
<li>
<a href="<?php echo $this->router->buildUrl($menu->getLink(), array('websiteid' => $this->website->getId())); ?>"><?php echo $menu->getTitle(); ?></a>
</li>
<?php
endif;
endforeach; ?>
</ul>
</div>
</nav>
<?php echo $this->renderScriptBlocks(); ?>
</body>
</html>