<?php
$this->assign('breadcrumb', array(
    array('text'=>$this->locale->_('auth_manage')),
    array('text'=>$this->locale->_('auth_role_list'), 'active'=>true)
));

$this->assign('buttons', array(
    array('text'=>$this->locale->_('add'), 'url'=>$this->router->buildUrl('add'))
));

$dt = $this->html->dataTable($this->models);
$dt->addIndexColumn('#', 'index', 'index');
$dt->addLabelColumn($this->locale->_('code'), '{code}');
$dt->addLabelColumn($this->locale->_('name'), '{name}');
$dt->addOptionColumn('', '{enabled}', array(
        ''=>'<span class="label label-danger">'.$this->locale->_('disabled').'</span>',
        0=>'<span class="label label-danger">'.$this->locale->_('disabled').'</span>',
        1=>'<span class="label label-success">'.$this->locale->_('enabled').'</span>'),
    'small', 'small');
$dt->addLinkColumn('#', $this->locale->_('edit'), $this->router->buildUrl('edit', array('id'=>'{id}')), 'small', 'small');
$dt->addButtonColumn('', $this->locale->_('delete'), "deleteConfirm('".urldecode($this->router->buildUrl('delete', array('id'=>'{id}')))."')", 'small', 'small');
$this->assign('datatable', $dt);

$p = $this->html->pagination($this->total, PAGINATION_SIZE, PAGINATION_RANGE);
$this->assign('pagination', $p);

echo $this->includeTemplate('layout\list');