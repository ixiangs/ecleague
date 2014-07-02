<?php
$this->assign('breadcrumb', array(
    $this->html->anchor($this->localize->_('user_manage')),
    $this->html->anchor($this->localize->_('user_behavior_list'))
));

$this->assign('navigationBar', array(
    $this->html->anchor($this->localize->_('add'), $this->router->buildUrl('add'))
));

$dt = $this->html->grid($this->models);
$dt->addIndexColumn('#', 'index', 'index');
$dt->addLabelColumn($this->localize->_('code'), '{code}', 'large', 'left');
$dt->addLabelColumn($this->localize->_('name'), '{name}', '', 'left');
$dt->addLabelColumn($this->localize->_('user_component'), '{component_name}', '', 'left');
//$dt->addLabelColumn($this->localize->_('url'), '{url}');
$dt->addBooleanColumn($this->localize->_('status'), 'enabled', $this->localize->_('enabled'), $this->localize->_('disabled').'</span>',
    'small', 'small text-center');
//$dt->addBooleanColumn($this->localize->_('status'), '{enabled}', 'small', 'small text-center');
$dt->addLinkColumn('', $this->localize->_('edit'), urldecode($this->router->buildUrl('edit', array('id'=>'{id}'))), 'edit', 'edit');
$dt->addLinkButtonColumn('', $this->localize->_('delete'), "deleteConfirm('".urldecode($this->router->buildUrl('delete', array('id'=>'{id}')))."')", 'edit', 'edit');
$this->assign('datatable', $dt);

$p = $this->html->pagination($this->total, PAGINATION_SIZE, PAGINATION_RANGE);
$this->assign('pagination', $p);

echo $this->includeTemplate('layout\list');