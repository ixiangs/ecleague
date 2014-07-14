<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $this->system->getWebsiteTitle(); ?></title>
    <link href="<?php echo CSS_URL; ?>bootstrap.css" rel="stylesheet">
    <link href="<?php echo CSS_URL; ?>font-awesome.css" rel="stylesheet">
    <link href="<?php echo CSS_URL; ?>style.css" rel="stylesheet">
    <?php echo $this->renderReferenceCss(); ?>
    <link href="<?php echo CSS_URL; ?>backend.css" rel="stylesheet">
    <script src="<?php echo JS_URL; ?>mootools.js"></script>
    <script src="<?php echo JS_URL; ?>locale.js"></script>
    <script src="<?php echo JS_URL; ?>toy/core.js"></script>
    <script src="<?php echo JS_URL; ?>toy/html.js"></script>
    <script src="<?php echo JS_URL; ?>backend.js"></script>
    <?php echo $this->renderReferenceScripts(); ?>
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body>
<?php echo $this->includeTemplate('alert'); ?>
<div class="contain">

    <!-- BEGIN LOGIN -->
    <div class="login">
        <div class="login-box">

            <!-- LOGIN LOGO -->
            <div class="log-logo">
                <img src="lib/img/logo3.png">
            </div>
            <form id="form1" method="post">
                <!-- LOGIN FORM -->
                <div class="log-contain">
                    <div class="form-group">
                        <label for="username"><?php echo $this->localize->_('username'); ?></label>
                        <input type="text" class="form-control col-sm-12" id="username" name="username">
                    </div>
                    <div class="form-group">
                        <label for="password"><?php echo $this->localize->_('password'); ?></label>
                        <input type="password" class="form-control col-sm-12" id="password" name="password">
                    </div>
                    <!--                    <div class="form-group">-->
                    <!--                        <input tabindex="13" type="checkbox" class="icheck-blue" id="fc1" checked>-->
                    <!--                        <label class="checkbox-label" for="fc1">Remember Me</label>-->
                    <!--                    </div>-->
                </div>

                <!-- LOGIN BUTTONS -->
                <div class="log-footer">
                    <!--                <div class="forgot"><a href="#">Forgot Password?</a></div>-->
                    <!--                <a href="index.html" class="btn btn-default">Cancel</a>&nbsp;&nbsp;-->
                    <!--                <a href="#" class="btn btn-info">Login</a>-->
                    <button type="submit" class="btn btn-info"><?php echo $this->localize->_('login'); ?></button>
                </div>
            </form>
        </div>

    </div>

</div>


</body>
</html>