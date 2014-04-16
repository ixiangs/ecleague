<?php
$this->assign('breadcrumb', array(
    $this->html->anchor($this->locale->_('auth_manage')),
    $this->html->anchor($this->locale->_('auth_account_list'))
));

$this->assign('navigationBar', array(
    $this->html->anchor($this->locale->_('add'), $this->router->buildUrl('add'))
));

$dt = $this->html->grid($this->models);
$dt->addIndexColumn('#', 'index', 'index');
$dt->addLabelColumn($this->locale->_('username'), '{username}', 'middle', 'middle');
$dt->addLabelColumn($this->locale->_('email'), '{email}');
$dt->addOptionColumn($this->locale->_('level'), '{level}', array(
        \Core\Auth\Model\AccountModel::LEVEL_ADMINISTRATOR=>$this->locale->_('auth_level_admin'),
        \Core\Auth\Model\AccountModel::LEVEL_NORMAL=>$this->locale->_('auth_level_normal')),
    'small', 'small text-center');
$dt->addOptionColumn($this->locale->_('status'), '{status}', array(
        \Core\Auth\Model\AccountModel::STATUS_ACTIVATED=>'<span class="text-success">'.$this->locale->_('auth_status_activated').'</span>',
        \Core\Auth\Model\AccountModel::STATUS_NONACTIVATED=>'<span class="text-warning">'.$this->locale->_('auth_status_nonactivated').'</span>',
        \Core\Auth\Model\AccountModel::STATUS_DISABLED=>'<span class="text-danger">'.$this->locale->_('disabled').'</span>'),
    'small', 'small text-center');
$dt->addLinkColumn('', $this->locale->_('edit'), urldecode($this->router->buildUrl('edit', array('id'=>'{id}'))), 'edit', 'edit');
$dt->addLinkButtonColumn('', $this->locale->_('delete'), "deleteConfirm('".urldecode($this->router->buildUrl('delete', array('id'=>'{id}')))."')", 'edit', 'edit');
$this->assign('datatable', $dt);

$p = $this->html->pagination($this->total, PAGINATION_SIZE, PAGINATION_RANGE);
$this->assign('pagination', $p);

echo $this->includeTemplate('layout\list');