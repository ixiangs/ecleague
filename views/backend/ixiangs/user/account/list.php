<?php
$this->assign('breadcrumb', array(
    $this->html->anchor($this->locale->_('user_manage')),
    $this->html->anchor($this->locale->_('user_account_list'))
));

$this->assign('navigationBar', array(
    $this->html->anchor($this->locale->_('add'), $this->router->buildUrl('add'))
));

$dt = $this->html->grid($this->models);
$dt->addIndexColumn('#', 'index', 'index');
$dt->addLabelColumn($this->locale->_('username'), '{username}', 'middle', 'middle');
$dt->addLabelColumn($this->locale->_('email'), '{email}');
$dt->addOptionColumn($this->locale->_('user_type'), '{type}', array(
        \Ixiangs\User\Constant::TYPE_ADMINISTRATOR=>$this->locale->_('user_type_admin'),
        \Ixiangs\User\Constant::TYPE_NORMAL=>$this->locale->_('user_type_normal')),
    'small', 'small text-center');
$dt->addOptionColumn($this->locale->_('status'), '{status}', array(
        \Ixiangs\User\Constant::STATUS_ACCOUNT_ACTIVATED=>'<span class="text-success">'.$this->locale->_('user_status_activated').'</span>',
        \Ixiangs\User\Constant::STATUS_ACCOUNT_NONACTIVATED=>'<span class="text-warning">'.$this->locale->_('user_status_nonactivated').'</span>',
        \Ixiangs\User\Constant::STATUS_ACCOUNT_DISABLED=>'<span class="text-danger">'.$this->locale->_('disabled').'</span>'),
    'small', 'small text-center');
$dt->addLinkColumn('', $this->locale->_('edit'), urldecode($this->router->buildUrl('edit', array('id'=>'{id}'))), 'edit', 'edit');
$dt->addLinkButtonColumn('', $this->locale->_('delete'), "deleteConfirm('".urldecode($this->router->buildUrl('delete', array('id'=>'{id}')))."')", 'edit', 'edit');
$this->assign('datatable', $dt);

$p = $this->html->pagination($this->total, PAGINATION_SIZE, PAGINATION_RANGE);
$this->assign('pagination', $p);

echo $this->includeTemplate('layout\list');