<?php
$this->assign('breadcrumb', array(
    $this->html->anchor($this->localize->_('catalogue_manage'))
));

$this->assign('navigationBar', array(
    $this->html->anchor($this->localize->_('add'), $this->router->buildUrl('add'))
));

$clang = $this->localize->getLanguage();
$dt = $this->html->grid($this->models);
$dt->addIndexColumn('#', 'index', 'index');
$dt->addLabelColumn('SKU', '{sku}', 'large', 'left');
$dt->addLabelColumn($this->localize->_('name'), '{name}', '', 'left');
$dt->addLinkColumn('', $this->localize->_('edit'), urldecode($this->router->buildUrl('edit', array('id'=>'{id}'))), 'edit', 'edit');
$dt->addLinkButtonColumn('', $this->localize->_('delete'), "deleteConfirm('".urldecode($this->router->buildUrl('delete', array('id'=>'{id}')))."')", 'edit', 'edit');
$this->assign('datatable', $dt);

$p = $this->html->pagination($this->total, PAGINATION_SIZE, PAGINATION_RANGE);
$this->assign('pagination', $p);

echo $this->includeTemplate('layout\list');