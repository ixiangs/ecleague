<?php
$this->assign('breadcrumb', array(
    array('text'=>$this->locale->_('auth_manage')),
    array('text'=>$this->locale->_('auth_behavior_list'), 'active'=>true)
));
$this->beginBlock('list');
$dt = $this->html->dataTable($this->models)->setCss('table table-striped table-bordered table-hover');
$dt->addLabelColumn($this->locale->_('code'), '{code}');
echo $dt->render();
$this->endBlock();

echo $this->includeTemplate('default_list');