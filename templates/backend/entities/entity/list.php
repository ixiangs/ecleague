<?php
$this->assign('navigationBar', array(
    $this->html->anchor($this->localize->_('entities_entity_new'), $this->router->buildUrl('add'))
));

$clang = $this->localize->getLanguage();
$dt = $this->html->grid($this->models);
$dt->addIndexColumn('#', 'index', 'index');
$dt->addLabelColumn($this->localize->_('entities_model'), '{model}', 'large', '');
$dt->addLabelColumn($this->localize->_('name'), '{name}', '', '');
//$dt->addLabelColumn($this->localize->_('memo'), '{memo}', '', '')
//    ->setCellRenderer(function($col, $row) use($clang){
//        $col->getCell()->getChild(0)->removeBindableAttribute('text')->setAttribute('text', $row->memo[$clang['id']]);
//        return $col->getCell()->renderBegin().$col->getCell()->renderInner().$col->getCell()->renderEnd();
//    });
$dt->addBooleanColumn($this->localize->_('status'), 'enabled', $this->localize->_('enabled'), $this->localize->_('disabled').'</span>',
    'small', 'small text-center');
$dt->addLinkColumn('', $this->localize->_('edit'), urldecode($this->router->buildUrl('edit', array('id' => '{id}'))), 'small', 'small edit');
$dt->addLinkColumn('', $this->localize->_('entities_field'), urldecode($this->router->buildUrl('field/list', array('entityid' => '{id}'))), 'small', 'small edit');
$dt->addLinkColumn('', $this->localize->_('grouping'), urldecode($this->router->buildUrl('groups', array('entityid' => '{id}'))), 'small', 'small edit');
$this->assign('datatable', $dt);

$p = $this->html->pagination($this->total, PAGINATION_SIZE, PAGINATION_RANGE);
$this->assign('pagination', $p);

echo $this->includeTemplate('layout\list');