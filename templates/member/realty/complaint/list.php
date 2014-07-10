<?php
$dt = $this->html->grid($this->models);
$dt->addLabelColumn($this->localize->_('realty_owner'), '@{contacts}', 'middle', 'left');
$dt->addLabelColumn($this->localize->_('phone'), '@{phone}', 'middle', 'left');
$dt->addLabelColumn($this->localize->_('realty_room_number'), '@{building}/{floor}/{room}', '', 'left');
$dt->addLabelColumn($this->localize->_('realty_complaint_time'), '@{created_time}', 'small', 'left');
$dt->addLinkColumn('', $this->localize->_('look'), '@'.urldecode($this->router->buildUrl('detail', array('id'=>'{id}'))), 'edit', 'edit');
$this->assign('datatable', $dt);

$p = $this->html->pagination($this->total, PAGINATION_SIZE, PAGINATION_RANGE);
$this->assign('pagination', $p);

echo $this->includeTemplate('layout\list');