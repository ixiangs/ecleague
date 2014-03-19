<?php
$this->assign('breadcrumb', array(
    array('text'=>$this->locale->_('auth_manage')),
    array('text'=>$this->locale->_('auth_behavior_list'), 'active'=>true)
));
$dt = $this->html->dataTable($this->models);
$dt->addIndexColumn('#', 'index', 'index');
$dt->addLabelColumn($this->locale->_('code'), '{code}', 'middle', 'middle');
$dt->addLabelColumn($this->locale->_('name'), '{name}', 'middle', 'middle');
$dt->addOptionColumn($this->locale->_('enable'), '{enabled}', array(
    ''=>'<span class="label label-danger">'.$this->locale->_('no').'</span>',
    0=>'<span class="label label-danger">'.$this->locale->_('no').'</span>',
    1=>'<span class="label label-success">'.$this->locale->_('yes').'</span>'),
    'small', 'small');
$dt->addLabelColumn($this->locale->_('url'), '{url}');
$dt->addLinkColumn('#', $this->locale->_('edit'), $this->router->buildUrl('edit', array('id'=>'{id}')), 'small', 'small');
$this->assign('datatable', $dt);

$p = $this->html->pagination($this->total, PAGINATION_SIZE, PAGINATION_RANGE);
$this->assign('pagination', $p);

echo $this->includeTemplate('layout\list');