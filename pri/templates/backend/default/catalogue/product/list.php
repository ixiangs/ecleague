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
$dt->addLabelColumn($this->locale->_('name'), '{name}', '', 'left')
    ->setCellRenderer(function($col, $row) use($clang){
        $col->getCell()->getChild(0)
            ->removeBindableAttribute('text')
            ->setAttribute('text', $row->name[$clang['id']]);
        return $col->getCell()->renderBegin().$col->getCell()->renderInner().$col->getCell()->renderEnd();
    });
//$dt->addLabelColumn($this->locale->_('name'), '{last_name} {first_name}', 'middle');
//$dt->addLabelColumn($this->locale->_('gender'), '{gender}', 'small');
//$dt->addLabelColumn($this->locale->_('email'), '{email}');
//$dt->addLabelColumn($this->locale->_('mobile'), '{mobile}', 'middle');
$dt->addLinkColumn('', $this->locale->_('edit'), urldecode($this->router->buildUrl('edit', array('id'=>'{id}'))), 'edit', 'edit');
$dt->addLinkButtonColumn('', $this->locale->_('delete'), "deleteConfirm('".urldecode($this->router->buildUrl('delete', array('id'=>'{id}')))."')", 'edit', 'edit');
$this->assign('datatable', $dt);

$p = $this->html->pagination($this->total, PAGINATION_SIZE, PAGINATION_RANGE);
$this->assign('pagination', $p);

echo $this->includeTemplate('layout\list');