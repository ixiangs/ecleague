<div class="subnavbar">

    <div class="subnavbar-inner">

        <div class="container">

            <a href="javascript:;" class="subnav-toggle" data-toggle="collapse" data-target=".subnav-collapse">
                <span class="sr-only">Toggle navigation</span>
                <i class="icon-reorder"></i>

            </a>

            <div class="collapse subnav-collapse">
                <ul class="mainnav">

                    <li class="dropdown">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
                            <span><?php echo $this->locale->_('system_manage'); ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="dropdown-submenu"><a href="#"><?php echo $this->locale->_('auth_manage'); ?></a>
                                <ul class="dropdown-menu">
                                    <li><a class="sbtn"
                                           href="<?php echo $this->router->buildUrl('auth/account/list'); ?>"><?php echo $this->locale->_("auth_account_list"); ?></a>
                                    </li>
                                    <li><a class="sbtn"
                                           href="<?php echo $this->router->buildUrl('auth/role/list'); ?>"><?php echo $this->locale->_("auth_role_list"); ?></a>
                                    </li>
                                    <li><a class="sbtn"
                                           href="<?php echo $this->router->buildUrl('auth/behavior/list'); ?>"><?php echo $this->locale->_("auth_behavior_list"); ?></a>
                                    </li>
                                </ul>
                            </li>
                            <li><a class="sbtn"
                                   href="<?php echo $this->router->buildUrl('locale/language/list'); ?>"><?php echo $this->locale->_('locale_manage'); ?>
</a>
                            </li>
                            <li class="dropdown-submenu"><a href="#"><?php echo $this->locale->_('attrs_manage'); ?></a>
                                <ul class="dropdown-menu">
                                    <li><a class="sbtn"
                                           href="<?php echo $this->router->buildUrl('attrs/attribute/list'); ?>"><?php echo $this->locale->_("attrs_attribute_list"); ?></a>
                                    </li>
                                    <li><a class="sbtn"
                                           href="<?php echo $this->router->buildUrl('attrs/attribute-group/list'); ?>"><?php echo $this->locale->_("attrs_attribute_group"); ?></a>
                                    </li>
                                    <li><a class="sbtn"
                                           href="<?php echo $this->router->buildUrl('attrs/attribute-set/list'); ?>"><?php echo $this->locale->_("attrs_attribute_set"); ?></a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>

                </ul>
            </div>
            <!-- /.subnav-collapse -->

        </div>
        <!-- /container -->

    </div>
    <!-- /subnavbar-inner -->

</div>