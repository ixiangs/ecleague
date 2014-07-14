<div class="sidebar">
    <ul id="nav" style="">
        <li><a href="/admin">
                <span><?php echo $this->localize->_('dashboard'); ?></span></a>
        </li>
        <li class="has_sub">
            <a href="#"> <span><?php echo $this->localize->_('weiweb_manage'); ?></span>
                <span class="pull-right"><i class="fa fa-chevron-left"></i></span></a>
            <ul>
                <li><a href="<?php echo $this->router->buildUrl('void_weiweb/website/list'); ?>"><?php echo $this->localize->_('weiweb_website_list'); ?></a></li>
            </ul>
        </li>
        <li class="has_sub">
            <a href="#"> <span><?php echo $this->localize->_('content_manage'); ?></span>
                <span class="pull-right"><i class="fa fa-chevron-left"></i></span></a>
            <ul>
                <li><a href="<?php echo $this->router->buildUrl('void_content/article/list'); ?>"><?php echo $this->localize->_('content_article_list'); ?></a></li>
                <li><a href="<?php echo $this->router->buildUrl('void_content/category/list'); ?>"><?php echo $this->localize->_('content_category_list'); ?></a></li>
                <li><a href="<?php echo $this->router->buildUrl('void_content/publisher/list'); ?>"><?php echo $this->localize->_('content_publisher_list'); ?></a></li>
            </ul>
        </li>
        <li class="has_sub">
            <a href="#"> <span><?php echo $this->localize->_('realty_manage'); ?></span>
                <span class="pull-right"><i class="fa fa-chevron-left"></i></span></a>
            <ul>
                <li><a href="<?php echo $this->router->buildUrl('void_realty/developer/list'); ?>"><?php echo $this->localize->_('realty_developer_list'); ?></a></li>
                <li><a href="<?php echo $this->router->buildUrl('void_realty/uptown/list'); ?>"><?php echo $this->localize->_('realty_uptown_list'); ?></a></li>
            </ul>
        </li>
        <li class="has_sub">
            <a href="#"> <span><?php echo $this->localize->_('auth_manage'); ?></span>
                <span class="pull-right"><i class="fa fa-chevron-left"></i></span></a>
            <ul>
                <li><a href="<?php echo $this->router->buildUrl('void_auth/account/list'); ?>"><?php echo $this->localize->_('auth_account_list'); ?></a></li>
                <li><a href="<?php echo $this->router->buildUrl('void_auth/role/list'); ?>"><?php echo $this->localize->_('auth_role_list'); ?></a></li>
                <li><a href="<?php echo $this->router->buildUrl('void_auth/behavior/list'); ?>"><?php echo $this->localize->_('auth_behavior_list'); ?></a></li>
                <li><a href="<?php echo $this->router->buildUrl('void_auth/group/list'); ?>"><?php echo $this->localize->_('auth_group_list'); ?></a></li>
            </ul>
        </li>
        <li class="has_sub">
            <a href="#"> <span><?php echo $this->localize->_('system_manage'); ?></span>
                <span class="pull-right"><i class="fa fa-chevron-left"></i></span></a>
            <ul>
                <li><a href="<?php echo $this->router->buildUrl('void_system/component/list'); ?>"><?php echo $this->localize->_('system_component_list'); ?></a></li>
                <li><a href="<?php echo $this->router->buildUrl('void_system/setting/edit'); ?>"><?php echo $this->localize->_('system_setting'); ?></a></li>
            </ul>
        </li>
<!--        <li class="has_sub"><a href="#"><i class="fa fa-folder"></i> <span>4 Level Menu</span> <span class="pull-right"><i class="fa fa-chevron-left"></i></span></a>-->
<!--            <ul>-->
<!--                <li><a href="#"><i class="fa fa-thumb-tack"></i> Subitem 1</a></li>-->
<!--                <li class="has_sub"><a href="#"><i class="fa fa-thumbs-up"></i> Subitem 2 <span class="pull-right"><i class="fa fa-chevron-left"></i></span></a>-->
<!--                    <ul>-->
<!--                        <li><a href="#"><i class="fa fa-trophy"></i> Subitem 1</a></li>-->
<!--                        <li class="has_sub"><a href="#"><i class="fa fa-share"></i> Subitem 2 <span class="pull-right"><i class="fa fa-chevron-left"></i></span></a>-->
<!--                            <ul>-->
<!--                                <li><a href="#"><i class="fa fa-microphone"></i> Subitem 1</a></li>-->
<!--                                <li><a href="#"><i class="fa fa-phone"></i> Subitem 2</a></li>-->
<!--                            </ul>-->
<!--                        </li>-->
<!--                    </ul>-->
<!--                </li>-->
<!--            </ul>-->
<!--        </li>-->
    </ul>
</div>