<?php
$this->assign('navigationBar', array(
    $this->html->anchor($this->localize->_('user_role_new'), $this->router->buildUrl('add'))
));

$dt = $this->html->grid($this->models);
$dt->addIndexColumn('#', 'index', 'index');
$dt->addLabelColumn($this->localize->_('code'), '{code}', 'large', 'left');
$dt->addLabelColumn($this->localize->_('name'), '{name}', '', 'left');
$dt->addBooleanColumn($this->localize->_('enable'), 'enabled', $this->localize->_('yes'), $this->localize->_('no'),
    'small');
$dt->addLinkColumn('', $this->localize->_('edit'), urldecode($this->router->buildUrl('edit', array('id'=>'{id}'))), 'edit', 'edit');
$dt->addLinkButtonColumn('', $this->localize->_('delete'), "deleteConfirm('".urldecode($this->router->buildUrl('delete', array('id'=>'{id}')))."')", 'edit', 'edit');
$this->assign('datatable', $dt);

$p = $this->html->pagination($this->total, PAGINATION_SIZE, PAGINATION_RANGE);
$this->assign('pagination', $p);

echo $this->includeTemplate('layout\list');