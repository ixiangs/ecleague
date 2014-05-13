<?php
$this->assign('navigationBar', array(
    $this->html->anchor($this->locale->_('add'), $this->router->buildUrl('add'))
));

$dt = $this->html->grid($this->models);
$dt->addIndexColumn('#', 'index', 'index');
$dt->addLabelColumn($this->locale->_('name'), '{name}', 'middle', 'left');
$dt->addLabelColumn($this->locale->_('office_department'), '{department_name}', 'middle', 'left');
$dt->addLabelColumn($this->locale->_('office_position'), '{position_name}', 'middle', 'left');
$dt->addLabelColumn($this->locale->_('work_email'), '{work_email}', '', 'left');
$dt->addLabelColumn($this->locale->_('telephone'), '{telephone}', 'middle', 'left');
$dt->addLabelColumn($this->locale->_('mobile'), '{mobile}', 'middle', 'left');
$dt->addLinkColumn('', $this->locale->_('edit'), urldecode($this->router->buildUrl('edit', array('id'=>'{id}'))), 'edit', 'edit');
$dt->addLinkButtonColumn('', $this->locale->_('delete'), "deleteConfirm('".urldecode($this->router->buildUrl('delete', array('id'=>'{id}')))."')", 'edit', 'edit');
$this->assign('datatable', $dt);

$p = $this->html->pagination($this->total, PAGINATION_SIZE, PAGINATION_RANGE);
$this->assign('pagination', $p);

echo $this->includeTemplate('layout\list');