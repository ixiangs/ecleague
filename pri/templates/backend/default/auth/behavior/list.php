<?php
$this->assign('breadcrumb', array(
    array('text'=>$this->locale->_('auth_manage')),
    array('text'=>$this->locale->_('auth_behavior_list'), 'active'=>true)
));
$dt = $this->html->dataTable($this->models);
$dt->addIndexColumn('#', 'index', 'index');
$dt->addLabelColumn($this->locale->_('code'), '{code}', 'middle', 'middle');
$dt->addLabelColumn($this->locale->_('name'), '{name}', 'middle', 'middle');
$dt->addOptionColumn($this->locale->_('enable'), '{enabled}', array(''=>$this->locale->_('no'), 0=>$this->locale->_('no'), 1=>$this->locale->_('yes')), 'small', 'small');
$dt->addLabelColumn($this->locale->_('url'), '{url}');
$dt->addLinkColumn('#', $this->locale->_('edit'), $this->router->buildUrl('edit', array('id'=>'{id}')), 'small', 'small');
$this->assign('datatable', $dt);

echo $this->includeTemplate('layout\list');