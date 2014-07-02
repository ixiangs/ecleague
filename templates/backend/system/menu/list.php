<?php
$this->assign('navigationBar', array(
    $this->html->anchor($this->localize->_('add'), $this->router->buildUrl('add')),
    $this->html->anchor($this->localize->_('sort'), $this->router->buildUrl('sort'))
));

$clang = $this->localize->getLanguage();
$dt = $this->html->grid($this->models);
$dt->addIndexColumn('#', 'index', 'index');
$dt->addLabelColumn($this->localize->_('name'), '{name}', 'large', 'left');
$dt->addLabelColumn($this->localize->_('url'), '{url}', '', 'left');
$dt->addBooleanColumn($this->localize->_('status'), 'enabled', $this->localize->_('enabled'), $this->localize->_('disabled').'</span>',
    'small', 'small text-center');
$dt->addLinkColumn('', $this->localize->_('edit'), urldecode($this->router->buildUrl('edit', array('id' => '{id}'))), 'small', 'small edit');
$dt->addLinkButtonColumn('', $this->localize->_('delete'), "deleteConfirm('".urldecode($this->router->buildUrl('delete', array('id'=>'{id}')))."')", 'edit', 'edit');

$this->assign('datatable', $dt);

$p = $this->html->pagination($this->total, PAGINATION_SIZE, PAGINATION_RANGE);
$this->assign('pagination', $p);

echo $this->includeTemplate('layout\list');