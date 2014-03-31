<?php
$this->assign('breadcrumb', array(
    $this->html->anchor($this->locale->_('auth_manage')),
    $this->html->anchor($this->locale->_('auth_behavior_list'))
));

$this->assign('navigationBar', array(
    $this->html->anchor($this->locale->_('add'), $this->router->buildUrl('add'))
));

$dt = $this->html->table($this->models);
$dt->addIndexColumn('#', 'index', 'index');
$dt->addLabelColumn($this->locale->_('code'), '{code}', 'middle', 'middle');
$dt->addLabelColumn($this->locale->_('name'), '{name}', 'middle', 'middle');
$dt->addLabelColumn($this->locale->_('url'), '{url}');
$dt->addBooleanColumn($this->locale->_('enable'), '{enabled}', 'small', 'small text-center');
$dt->addLinkColumn('', '<i class="fa fa-edit fa-2x"></i>', urldecode($this->router->buildUrl('edit', array('id'=>'{id}'))), 'edit', 'edit')
        ->getLink()->setAttribute('title', $this->locale->_('edit'));
$dt->addLinkColumn('', '<i class="fa fa-times fa-2x"></i>', "javascript:deleteConfirm('".urldecode($this->router->buildUrl('delete', array('id'=>'{id}')))."')", 'edit', 'edit')
        ->getLink()->setAttribute('title', $this->locale->_('delete'));
$this->assign('datatable', $dt);

$p = $this->html->pagination($this->total, PAGINATION_SIZE, PAGINATION_RANGE);
$this->assign('pagination', $p);

echo $this->includeTemplate('layout\list');