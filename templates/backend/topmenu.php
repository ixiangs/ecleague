<header>
    <div class="navbar navbar-fixed-top bs-docs-nav" role="banner">

        <div class="container">
            <!-- Menu button for smallar screens -->
            <div class="navbar-header">
                <button class="navbar-toggle btn-navbar" type="button" data-toggle="collapse"
                        data-target=".bs-navbar-collapse"><span>Menu</span></button>
                <a href="#" class="pull-left menubutton hidden-xs"><i class="fa fa-bars"></i></a>
                <!-- Site name for smallar screens -->
                <a href="index.html" class="navbar-brand">
                    <?php echo $this->system->getWebsiteTitle(); ?>
                </a>
            </div>

            <!-- Navigation starts -->
            <nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">

                <!-- Links -->
                <ul class="nav navbar-nav pull-right">
                    <li class="dropdown pull-right user-data">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                           <?php echo $this->identity->getUsername(); ?> <b class="caret"></b>
                        </a>
                        <!-- Dropdown menu -->
                        <ul class="dropdown-menu">
<!--                            <li><a href="#"><i class="fa fa-user"></i> Profile</a></li>-->
<!--                            <li><a href="#"><i class="fa fa-cogs"></i> Settings</a></li>-->
                            <li><a href="<?php echo $this->router->buildUrl('void_index/passport/logout'); ?>"><i class="fa fa-key"></i><?php echo $this->localize->_('logout') ?></a></li>
                        </ul>
                    </li>
                </ul>
            </nav>

        </div>
    </div>
</header>