<div class="left hidden-xs">
    <div class="sidebar">
        <div class="accordion">
            <div class="accordion-group">
                <div class="accordion-heading">
                    <a class="sbtn" data-toggle="collapse" href="#c-ui">
                        <span class="full"><?php echo $this->locale->_("auth_manage"); ?></span><span
                            class="caret"></span>
                    </a>
                </div>
                <div id="c-ui" class="accordion-body collapse">
                    <div class="accordion-inner">
                        <a class="sbtn" href="<?php echo $this->router->buildUrl('auth/account/list'); ?>"><?php echo $this->locale->_("auth_account_list"); ?></a>
                        <a class="sbtn" href="<?php echo $this->router->buildUrl('auth/role/list'); ?>"><?php echo $this->locale->_("auth_role_list"); ?></a>
                        <a class="sbtn" href="<?php echo $this->router->buildUrl('auth/behavior/list'); ?>"><?php echo $this->locale->_("auth_behavior_list"); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>