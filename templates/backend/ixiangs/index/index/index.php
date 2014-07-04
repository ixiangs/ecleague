<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo $this->localize->_('website_title'); ?></title>
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/static/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="/static/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/static/css/fonts.css">
    <link rel="stylesheet" type="text/css" href="/static/css/sb-admin.css">
    <link rel="stylesheet" type="text/css" href="/static/css/admin.css">
    <script src="/static/js/libs.js"></script>
    <script src="/static/js/bootstrap.min.js"></script>
    <script src="/static/js/toy/core.js"></script>
    <script src="/static/js/toy/html.js"></script>
    <script src="/static/js/locale.js"></script>
    <script src="/static/js/common.js"></script>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="/static/js/html5shiv.js"></script>
    <script src="/static/js/respond.min.js"></script>
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