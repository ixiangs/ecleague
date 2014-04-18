<?php
$this->assign('breadcrumb', array(
    $this->html->anchor($this->locale->_('admin_menu_manage'))
));

$this->assign('navigationBar', array(
    $this->html->anchor($this->locale->_('add'), $this->router->buildUrl('add')),
    $this->html->anchor($this->locale->_('sort'), $this->router->buildUrl('sort'))
));

$clang = $this->locale->getCurrentLanguage();
$dt = $this->html->grid($this->models);
$dt->addIndexColumn('#', 'index', 'index');
$dt->addLabelColumn($this->locale->_('name'), '{name}', 'large', 'left')
    ->setCellRenderer(function($col, $row) use($clang){
        $col->getCell()->getChild(0)->removeBindableAttribute('text')->setAttribute('text', $row->names[$clang['id']]);
        return $col->getCell()->renderBegin().$col->getCell()->renderInner().$col->getCell()->renderEnd();
    });
$dt->addLabelColumn($this->locale->_('url'), '{url}', '', 'left');
$dt->addBooleanColumn($this->locale->_('status'), 'enabled', $this->locale->_('enabled'), $this->locale->_('disabled').'</span>',
    'small', 'small text-center');
$dt->addLinkColumn('', $this->locale->_('edit'), urldecode($this->router->buildUrl('edit', array('id' => '{id}'))), 'small', 'small edit');

$this->assign('datatable', $dt);

$p = $this->html->pagination($this->total, PAGINATION_SIZE, PAGINATION_RANGE);
$this->assign('pagination', $p);

echo $this->includeTemplate('layout\list');