<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo $this->locale->_('website_title'); ?></title>
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/pub/assets/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="/pub/assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/pub/assets/css/fonts.css">
    <link rel="stylesheet" type="text/css" href="/pub/assets/css/sb-admin.css">
    <link rel="stylesheet" type="text/css" href="/pub/assets/css/admin.css">
    <script src="/pub/assets/js/libs.js"></script>
    <script src="/pub/assets/js/bootstrap.min.js"></script>
    <script src="/pub/assets/js/toy/core.js"></script>
    <script src="/pub/assets/js/toy/html.js"></script>
    <script src="/pub/assets/js/toy/locale.js"></script>
    <script src="/pub/assets/js/common.js"></script>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="/pub/assets/js/html5shiv.js"></script>
    <script src="/pub/assets/js/respond.min.js"></script>
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
                        <label for="username"><?php echo $this->locale->_('username'); ?></label>
                        <input type="text" class="form-control col-sm-12" id="username" name="username">
                    </div>
                    <div class="form-group">
                        <label for="password"><?php echo $this->locale->_('password'); ?></label>
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
                    <button type="submit" class="btn btn-info"><?php echo $this->locale->_('login'); ?></button>
                </div>
            </form>
        </div>

    </div>

</div>


</body>
</html>