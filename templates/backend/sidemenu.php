<div class="sidebar">
    <ul id="nav" style="">
        <li><a href="/admin">
                <span><?php echo $this->localize->_('dashboard'); ?></span></a>
        </li>
        <li class="has_sub">
            <a href="#"> <span><?php echo $this->localize->_('content_manage'); ?></span>
                <span class="pull-right"><i class="fa fa-chevron-left"></i></span></a>
            <ul>
                <li><a href="<?php echo $this->router->buildUrl('content/article/list'); ?>"><?php echo $this->localize->_('content_article_list'); ?></a></li>
                <li><a href="<?php echo $this->router->buildUrl('content/category/list'); ?>"><?php echo $this->localize->_('content_category_list'); ?></a></li>
                <li><a href="<?php echo $this->router->buildUrl('content/publisher/list'); ?>"><?php echo $this->localize->_('content_publisher_list'); ?></a></li>
            </ul>
        </li>
        <li class="has_sub">
            <a href="#"> <span><?php echo $this->localize->_('realty_manage'); ?></span>
                <span class="pull-right"><i class="fa fa-chevron-left"></i></span></a>
            <ul>
                <li><a href="<?php echo $this->router->buildUrl('realty/developer/list'); ?>"><?php echo $this->localize->_('realty_developer_list'); ?></a></li>
                <li><a href="<?php echo $this->router->buildUrl('realty/uptown/list'); ?>"><?php echo $this->localize->_('realty_uptown_list'); ?></a></li>
            </ul>
        </li>
        <li class="has_sub">
            <a href="#"> <span><?php echo $this->localize->_('user_manage'); ?></span>
                <span class="pull-right"><i class="fa fa-chevron-left"></i></span></a>
            <ul>
                <li><a href="<?php echo $this->router->buildUrl('user/account/list'); ?>"><?php echo $this->localize->_('user_account_list'); ?></a></li>
                <li><a href="<?php echo $this->router->buildUrl('user/role/list'); ?>"><?php echo $this->localize->_('user_role_list'); ?></a></li>
                <li><a href="<?php echo $this->router->buildUrl('user/behavior/list'); ?>"><?php echo $this->localize->_('user_behavior_list'); ?></a></li>
            </ul>
        </li>
        <li class="has_sub">
            <a href="#"> <span><?php echo $this->localize->_('system_manage'); ?></span>
                <span class="pull-right"><i class="fa fa-chevron-left"></i></span></a>
            <ul>
                <li><a href="<?php echo $this->router->buildUrl('system/component/list'); ?>"><?php echo $this->localize->_('system_component_list'); ?></a></li>
                <li><a href="<?php echo $this->router->buildUrl('system/setting/edit'); ?>"><?php echo $this->localize->_('system_setting'); ?></a></li>
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