<?php
$this->assign('breadcrumb', array(
    $this->html->anchor($this->locale->_('auth_manage')),
    $this->html->anchor($this->locale->_('auth_behavior_list'))
));

$this->assign('navigationBar', array(
    $this->html->anchor($this->locale->_('add'), $this->router->buildUrl('add'))
));

$dt = $this->html->grid($this->models);
$dt->addIndexColumn('#', 'index', 'index');
$dt->addLabelColumn($this->locale->_('code'), '{code}', 'middle', 'middle');
$dt->addLabelColumn($this->locale->_('name'), '{name}', 'middle', 'middle');
$dt->addLabelColumn($this->locale->_('url'), '{url}');
$dt->addBooleanColumn($this->locale->_('status'), 'enabled', $this->locale->_('enabled'), $this->locale->_('disabled').'</span>',
    'small', 'small text-center');
//$dt->addBooleanColumn($this->locale->_('status'), '{enabled}', 'small', 'small text-center');
$dt->addLinkColumn('', $this->locale->_('edit'), urldecode($this->router->buildUrl('edit', array('id'=>'{id}'))), 'edit', 'edit');
$dt->addLinkButtonColumn('', $this->locale->_('delete'), "deleteConfirm('".urldecode($this->router->buildUrl('delete', array('id'=>'{id}')))."')", 'edit', 'edit');
$this->assign('datatable', $dt);

$p = $this->html->pagination($this->total, PAGINATION_SIZE, PAGINATION_RANGE);
$this->assign('pagination', $p);

echo $this->includeTemplate('layout\list');