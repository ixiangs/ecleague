<?php
$this->assign('breadcrumb', array(
    $this->html->anchor($this->locale->_('catalogue_manage'))
));

$this->assign('navigationBar', array(
    $this->html->anchor($this->locale->_('add'), $this->router->buildUrl('add'))
));

$clang = $this->locale->getCurrentLanguage();
$dt = $this->html->grid($this->models);
$dt->addIndexColumn('#', 'index', 'index');
$dt->addLabelColumn('SKU', '{sku}', 'large', 'left');
$dt->addLabelColumn($this->locale->_('name'), '{name}', '', 'left');
$dt->addLinkColumn('', $this->locale->_('edit'), urldecode($this->router->buildUrl('edit', array('id'=>'{id}'))), 'edit', 'edit');
$dt->addLinkButtonColumn('', $this->locale->_('delete'), "deleteConfirm('".urldecode($this->router->buildUrl('delete', array('id'=>'{id}')))."')", 'edit', 'edit');
$this->assign('datatable', $dt);

$p = $this->html->pagination($this->total, PAGINATION_SIZE, PAGINATION_RANGE);
$this->assign('pagination', $p);

echo $this->includeTemplate('layout\list');