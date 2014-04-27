<?php
$this->assign('breadcrumb', array(
    $this->html->anchor($this->locale->_('attrs_manage')),
    $this->html->anchor($this->locale->_('attrs_attribute_set'))
));

$this->assign('navigationBar', array(
    $this->html->anchor($this->locale->_('attrs_new_attribute_set'), $this->router->buildUrl('add'))
));

$clang = $this->locale->getCurrentLanguage();
$dt = $this->html->grid($this->models);
$dt->addIndexColumn('#', 'index', 'index');
$dt->addLabelColumn($this->locale->_('code'), '{code}', 'middle', 'text-center');
$dt->addLabelColumn($this->locale->_('name'), '{name}', 'large', '')
    ->setCellRenderer(function($col, $row) use($clang){
        $col->getCell()->getChild(0)->removeBindableAttribute('text')->setAttribute('text', $row->name[$clang['id']]);
        return $col->getCell()->renderBegin().$col->getCell()->renderInner().$col->getCell()->renderEnd();
    });
$dt->addLabelColumn($this->locale->_('memo'), '{memo}', '', '')
    ->setCellRenderer(function($col, $row) use($clang){
        $col->getCell()->getChild(0)->removeBindableAttribute('text')->setAttribute('text', $row->memo[$clang['id']]);
        return $col->getCell()->renderBegin().$col->getCell()->renderInner().$col->getCell()->renderEnd();
    });
$dt->addBooleanColumn($this->locale->_('status'), 'enabled', $this->locale->_('enabled'), $this->locale->_('disabled').'</span>',
    'small', 'small text-center');
$dt->addLinkColumn('', $this->locale->_('edit'), urldecode($this->router->buildUrl('edit', array('id' => '{id}'))), 'small', 'small edit');
$dt->addLinkColumn('', $this->locale->_('edit'), urldecode($this->router->buildUrl('groups', array('id' => '{id}'))), 'small', 'small edit');
$this->assign('datatable', $dt);

$p = $this->html->pagination($this->total, PAGINATION_SIZE, PAGINATION_RANGE);
$this->assign('pagination', $p);

echo $this->includeTemplate('layout\list');