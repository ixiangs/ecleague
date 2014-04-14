<?php
$returnTo = $this->router->buildUrl('language/list');
$this->assign('breadcrumb', array(
    $this->html->anchor($this->locale->_('locale_manage')),
    $this->html->anchor($this->language->getName()),
    $this->html->anchor($this->locale->_('locale_dictionary'))
));

$this->assign('navigationBar', array(
    $this->html->anchor($this->locale->_('back'), $returnTo),
    $this->html->anchor($this->locale->_('add'), $this->router->buildUrl('add', array('languageid'=>$this->language->getId())))
));

$this->assign('toolbar', array(
    $this->html->button('button', $this->locale->_('delete'), 'btn btn-danger')
        ->setEvent('click', "deleteSelectedRow('table1', '".$this->router->buildUrl('delete', array('languageid'=>$this->language->getId()))."')")
));

$dt = $this->html->table($this->models);
$dt->addSelectableColumn('ids[]', '{id}', null, 'index', 'index');
$dt->addIndexColumn('', 'index', 'index');
$dt->addLabelColumn($this->locale->_('code'), '{code}', 'large', 'left');
$dt->addLabelColumn($this->locale->_('text'), '{label}', '', 'left');
$dt->addLinkColumn('', $this->locale->_('edit'), urldecode($this->router->buildUrl('edit', array('languageid'=>$this->language->getId(), 'id'=>'{id}'))), 'small', 'edit text-center');
$this->assign('datatable', $dt);

$this->assign('pagination', $this->html->pagination($this->total, PAGINATION_SIZE, PAGINATION_RANGE));

echo $this->includeTemplate('layout\list');