<?php
$this->assign('breadcrumb', array(
    $this->html->anchor($this->locale->_('attrs_manage')),
    $this->html->anchor($this->locale->_('attrs_attribute_group'))
));

$this->assign('navigationBar', array(
    $this->html->anchor($this->locale->_('add'), $this->router->buildUrl('add'))
));

$clang = $this->locale->getCurrentLanguage();
$dt = $this->html->grid($this->models);
$dt->addIndexColumn('#', 'index', 'index');
$dt->addLabelColumn($this->locale->_('name'), '{name}', 'large', '')
    ->setCellRenderer(function($col, $row) use($clang){
        $col->getCell()->getChild(0)->removeBindableAttribute('text')->setAttribute('text', $row->name[$clang['id']]);
        return $col->getCell()->renderBegin().$col->getCell()->renderInner().$col->getCell()->renderEnd();
    });
$dt->addLabelColumn($this->locale->_('name'), '{memo}', '', '')
    ->setCellRenderer(function($col, $row) use($clang){
        $col->getCell()->getChild(0)->removeBindableAttribute('text')->setAttribute('text', $row->memo[$clang['id']]);
        return $col->getCell()->renderBegin().$col->getCell()->renderInner().$col->getCell()->renderEnd();
    });
$dt->addBooleanColumn($this->locale->_('status'), 'enabled', $this->locale->_('enabled'), $this->locale->_('disabled').'</span>',
    'small', 'small');
$dt->addLinkColumn('', $this->locale->_('edit'), urldecode($this->router->buildUrl('edit', array('id' => '{id}'))), 'edit', 'edit');

$this->assign('datatable', $dt);

$p = $this->html->pagination($this->total, PAGINATION_SIZE, PAGINATION_RANGE);
$this->assign('pagination', $p);

echo $this->includeTemplate('layout\list');