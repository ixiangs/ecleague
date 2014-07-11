<div class="sidebar">
    <ul id="nav" style="">
        <li><a href="<?php echo '/'.$this->router->domain->getName(); ?>">
                <span><?php echo $this->localize->_('dashboard'); ?></span></a>
        </li>
        <?php if($this->session->get('publisherId')): ?>
        <li class="has_sub">
            <a href="#"> <span><?php echo $this->localize->_('content_manage'); ?></span>
                <span class="pull-right"><i class="fa fa-chevron-left"></i></span></a>
            <ul>
                <li><a href="<?php echo $this->router->buildUrl('content/article/list'); ?>"><?php echo $this->localize->_('content_article_list'); ?></a></li>
                <li><a href="<?php echo $this->router->buildUrl('content/category/list'); ?>"><?php echo $this->localize->_('content_category_list'); ?></a></li>
            </ul>
        </li>
        <?php endif; ?>
        <?php if($this->session->get('uptownId')): ?>
            <li class="has_sub">
                <a href="#"> <span><?php echo $this->localize->_('realty_uptown_manage'); ?></span>
                    <span class="pull-right"><i class="fa fa-chevron-left"></i></span></a>
                <ul>
                    <li><a href="<?php echo $this->router->buildUrl('realty/building/list'); ?>"><?php echo $this->localize->_('realty_building_list'); ?></a></li>
                    <li><a href="<?php echo $this->router->buildUrl('realty/repair/list'); ?>"><?php echo $this->localize->_('realty_repair_list'); ?></a></li>
                    <li><a href="<?php echo $this->router->buildUrl('realty/complaint/list'); ?>"><?php echo $this->localize->_('realty_complaint_list'); ?></a></li>
                </ul>
            </li>
        <?php endif;?>
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