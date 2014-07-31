<?php
//$this->assign('toolbar', array(
//    $this->html->anchor($this->localize->_('system_component_list'))
//));

//$clang = $this->localize->getLanguage();
$dt = $this->html->grid($this->models);
//$dt->addIndexColumn('#', 'index', 'index');
$dt->addLabelColumn($this->localize->_('code'), '@{code}', 'middle', 'left');
$dt->addLabelColumn($this->localize->_('name'), '@{name}', 'large', 'left');
$dt->addLabelColumn($this->localize->_('version'), '@{version}', 'small', 'text-center');
$dt->addLinkColumn($this->localize->_('author'), '@{author}', '@http://{website}', 'middle', 'text-center')
    ->getCell()->getChild(0)->setAttribute('target', '_blank');
$dt->addLabelColumn($this->localize->_('description'), '@{description}', '', 'left');

//$dt->addBooleanColumn($this->localize->_('status'), 'enabled', $this->localize->_('enabled'), $this->localize->_('disabled').'</span>',
//    'small', 'small text-center');
//$dt->addLinkColumn('', $this->localize->_('edit'), urldecode($this->router->buildUrl('edit', array('id' => '{id}'))), 'small', 'small edit');

$this->assign('datatable', $dt);

$p = $this->html->pagination($this->total, PAGINATION_SIZE, PAGINATION_RANGE);
$this->assign('pagination', $p);

echo $this->includeTemplate('layout\list');