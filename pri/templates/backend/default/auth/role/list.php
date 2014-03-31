<?php
$this->assign('breadcrumb', array(
    $this->html->anchor($this->locale->_('auth_manage')),
    $this->html->anchor($this->locale->_('auth_role_list'))
));

$this->assign('navigationBar', array(
    $this->html->anchor($this->locale->_('add'), $this->router->buildUrl('add'))
));

$dt = $this->html->table($this->models);
$dt->addIndexColumn('#', 'index', 'index');
$dt->addLabelColumn($this->locale->_('code'), '{code}');
$dt->addLabelColumn($this->locale->_('name'), '{name}');
$dt->addOptionColumn('', '{enabled}', array(
        ''=>'<span class="label label-danger">'.$this->locale->_('disabled').'</span>',
        0=>'<span class="label label-danger">'.$this->locale->_('disabled').'</span>',
        1=>'<span class="label label-success">'.$this->locale->_('enabled').'</span>'),
    'small', 'small');
$dt->addLinkColumn('', $this->locale->_('edit'), urldecode($this->router->buildUrl('edit', array('id'=>'{id}'))), 'edit', 'edit')
    ->getLink()->setAttribute('class', 'btn btn-default btn-sm');
$dt->addButtonColumn('', $this->locale->_('delete'), "deleteConfirm('".urldecode($this->router->buildUrl('delete', array('id'=>'{id}')))."')", 'edit', 'edit')
    ->getButton()->setAttribute('class', 'btn btn-default btn-sm');
$this->assign('datatable', $dt);

$p = $this->html->pagination($this->total, PAGINATION_SIZE, PAGINATION_RANGE);
$this->assign('pagination', $p);

echo $this->includeTemplate('layout\list');