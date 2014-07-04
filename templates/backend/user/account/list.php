<?php
$this->assign('toolbar', array(
    $this->html->anchor($this->localize->_('new'), $this->router->buildUrl('add'))
        ->setAttribute('class', 'btn btn-default')
));

$dt = $this->html->grid($this->models);
//$dt->addIndexColumn('#', 'index', 'index');
$dt->addLabelColumn($this->localize->_('username'), '@{username}', 'middle', 'middle');
$dt->addLabelColumn($this->localize->_('email'), '@{email}');
$dt->addStatusColumn($this->localize->_('type'), '@{type}', array(
        \Components\User\Constant::TYPE_ADMINISTRATOR=>$this->localize->_('user_type_admin'),
        \Components\User\Constant::TYPE_NORMAL=>$this->localize->_('user_type_normal')),
    'small', 'small text-center');
$dt->addStatusColumn($this->localize->_('status'), '@{status}', array(
        \Components\User\Constant::STATUS_ACCOUNT_ACTIVATED=>'<span class="label label-success">'.$this->localize->_('user_status_activated').'</span>',
        \Components\User\Constant::STATUS_ACCOUNT_NONACTIVATED=>'<span class="label label-warning">'.$this->localize->_('user_status_nonactivated').'</span>',
        \Components\User\Constant::STATUS_ACCOUNT_DISABLED=>'<span class="label label-danger">'.$this->localize->_('disabled').'</span>'),
    'small', 'small text-center');
$dt->addLinkColumn('', $this->localize->_('edit'), '@'.urldecode($this->router->buildUrl('edit', array('id'=>'{id}'))), 'edit', 'edit');
$dt->addButtonColumn('', $this->localize->_('delete'), "@deleteConfirm('".urldecode($this->router->buildUrl('delete', array('id'=>'{id}')))."')", 'edit', 'edit');
$this->assign('datatable', $dt);

$p = $this->html->pagination($this->total, PAGINATION_SIZE, PAGINATION_RANGE);
$this->assign('pagination', $p);

echo $this->includeTemplate('layout\list');