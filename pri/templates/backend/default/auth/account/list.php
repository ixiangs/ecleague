<?php
$this->assign('breadcrumb', array(
    array('text'=>$this->locale->_('auth_manage')),
    array('text'=>$this->locale->_('auth_account_list'), 'active'=>true)
));

$this->assign('buttons', array(
    array('text'=>$this->locale->_('add'), 'url'=>$this->router->buildUrl('add'))
));

$dt = $this->html->dataTable($this->models);
$dt->addIndexColumn('#', 'index', 'index');
$dt->addLabelColumn($this->locale->_('username'), '{username}', 'middle', 'middle');
$dt->addLabelColumn($this->locale->_('email'), '{email}');
$dt->addOptionColumn($this->locale->_('level'), '{level}', array(
        \Core\Auth\Model\AccountModel::LEVEL_ADMINISTRATOR=>$this->locale->_('auth_level_admin'),
        \Core\Auth\Model\AccountModel::LEVEL_NORMAL=>$this->locale->_('auth_level_normal')),
    'small', 'small');
$dt->addOptionColumn($this->locale->_('status'), '{status}', array(
        \Core\Auth\Model\AccountModel::STATUS_ACTIVATED=>'<span class="label label-success">'.$this->locale->_('auth_status_activated').'</span>',
        \Core\Auth\Model\AccountModel::STATUS_NONACTIVATED=>'<span class="label label-waring">'.$this->locale->_('auth_status_nonactivated').'</span>',
        \Core\Auth\Model\AccountModel::STATUS_DISABLED=>'<span class="label label-danger">'.$this->locale->_('disabled').'</span>'),
    'small', 'small');
$dt->addLinkColumn('', $this->locale->_('edit'), $this->router->buildUrl('edit', array('id'=>'{id}')), 'small', 'small');
$dt->addButtonColumn('', $this->locale->_('delete'), "deleteConfirm('".urldecode($this->router->buildUrl('delete', array('id'=>'{id}')))."')", 'small', 'small');
$this->assign('datatable', $dt);

$p = $this->html->pagination($this->total, PAGINATION_SIZE, PAGINATION_RANGE);
$this->assign('pagination', $p);

echo $this->includeTemplate('layout\list');