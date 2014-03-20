<?php
$this->assign('breadcrumb', array(
    array('text'=>$this->locale->_('locale_manage')),
    array('text'=>$this->locale->_('locale_language_list')),
    array('text'=>$this->language->getName(), 'active'=>true)
));

$this->assign('buttons', array(
    array('text'=>$this->locale->_('add'), 'url'=>$this->router->buildUrl('add', array('languageid'=>$this->language->getId())))
));

$dt = $this->html->table($this->models);
$dt->addIndexColumn('#', 'index', 'index');
$dt->addLabelColumn($this->locale->_('code'), '{code}', 'middle', 'middle');
$dt->addLabelColumn($this->locale->_('locale_label'), '{name}');
$dt->addButtonColumn('', $this->locale->_('delete'), "deleteConfirm('".urldecode($this->router->buildUrl('delete', array('id'=>'{id}')))."')", 'small', 'small');
$this->assign('datatable', $dt);

$p = $this->html->pagination($this->total, PAGINATION_SIZE, PAGINATION_RANGE);
$this->assign('pagination', $p);

echo $this->includeTemplate('layout\list');