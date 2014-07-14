<?php
$dt = $this->html->grid($this->models);
$dt->addLabelColumn($this->localize->_('name'), '@{name}', '', 'left');
$dt->addLabelColumn($this->localize->_('component'), '@{component_name}', '', 'left');
$dt->addLabelColumn($this->localize->_('user'), '@{username}', '', 'left');
//$dt->addLinkColumn('', $this->localize->_('edit'), '@'.urldecode($this->router->buildUrl('edit', array('id'=>'{id}'))), 'edit', 'edit');
$dt->addButtonColumn('', $this->localize->_('delete'), "@deleteConfirm('".urldecode($this->router->buildUrl('delete', array('id'=>'{id}')))."')", 'edit', 'edit');
$this->assign('datatable', $dt);

$p = $this->html->pagination($this->total, PAGINATION_SIZE, PAGINATION_RANGE);
$this->assign('pagination', $p);

echo $this->includeTemplate('layout\list');