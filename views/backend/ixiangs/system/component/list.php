<?php
$this->assign('breadcrumb', array(
    $this->html->anchor($this->locale->_('admin_component_manage'))
));

$clang = $this->locale->getCurrentLanguage();
$dt = $this->html->grid($this->models);
$dt->addIndexColumn('#', 'index', 'index');
$dt->addLabelColumn($this->locale->_('code'), '{code}', 'middle', 'left');
$dt->addLabelColumn($this->locale->_('name'), '{name}', 'large', 'left');
$dt->addLabelColumn($this->locale->_('version'), '{version}', 'small', 'text-center');
$dt->addLinkColumn($this->locale->_('author'), '{author}', 'http://{website}', 'middle', 'text-center')
    ->getCell()->getChild(0)->setAttribute('target', '_blank');
$dt->addLabelColumn($this->locale->_('description'), '{description}', '', 'left');

//$dt->addBooleanColumn($this->locale->_('status'), 'enabled', $this->locale->_('enabled'), $this->locale->_('disabled').'</span>',
//    'small', 'small text-center');
//$dt->addLinkColumn('', $this->locale->_('edit'), urldecode($this->router->buildUrl('edit', array('id' => '{id}'))), 'small', 'small edit');

$this->assign('datatable', $dt);

$p = $this->html->pagination($this->total, PAGINATION_SIZE, PAGINATION_RANGE);
$this->assign('pagination', $p);

echo $this->includeTemplate('layout\list');