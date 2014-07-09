<?php
//$this->assign('toolbar', array(
//    $this->html->anchor($this->localize->_('auth_manage')),
//    $this->html->anchor($this->localize->_('auth_behavior_list'))
//));

$this->assign('toolbar', array(
    $this->html->anchor($this->localize->_('new'), $this->router->buildUrl('add'))
));

$dt = $this->html->grid($this->models);
//$dt->addIndexColumn('#', 'index', 'index');
$dt->addLabelColumn($this->localize->_('code'), '@{code}', 'large', 'left');
$dt->addLabelColumn($this->localize->_('name'), '@{name}', '', 'left');
$dt->addLabelColumn($this->localize->_('component'), '@{component_name}', '', 'left');
//$dt->addLabelColumn($this->localize->_('url'), '{url}');
$dt->addStatusColumn($this->localize->_('enable'), '@{enabled}', array(
        1=>'<span class="label label-success">'.$this->localize->_('yes').'</span>',
        0=>'<span class="label label-danger">'.$this->localize->_('no').'</span>'),
    'small', 'small text-center');
$dt->addLinkColumn('', $this->localize->_('edit'), '@'.urldecode($this->router->buildUrl('edit', array('id'=>'{id}'))), 'edit', 'edit');
$dt->addButtonColumn('', $this->localize->_('delete'), "@deleteConfirm('".urldecode($this->router->buildUrl('delete', array('id'=>'{id}')))."')", 'edit', 'edit');
$this->assign('datatable', $dt);

$p = $this->html->pagination($this->total, PAGINATION_SIZE, PAGINATION_RANGE);
$this->assign('pagination', $p);

echo $this->includeTemplate('layout\list');