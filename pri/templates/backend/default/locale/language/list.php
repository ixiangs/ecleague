<?php
$this->assign('breadcrumb', array(
    array('text'=>$this->locale->_('locale_manage')),
    array('text'=>$this->locale->_('locale_language_list'), 'active'=>true)
));

$this->assign('buttons', array(
    array('text'=>$this->locale->_('add'), 'url'=>$this->router->buildUrl('add'))
));

$dt = $this->html->table($this->models);
$dt->addIndexColumn('#', 'index', 'index');
$dt->addLabelColumn($this->locale->_('locale_langauge_code'), '{code}', 'small', 'small');
$dt->addLabelColumn($this->locale->_('name'), '{name}', 'middle', 'middle');
$dt->addLabelColumn($this->locale->_('locale_timezone'), '{timezone}', 'middle', 'middle');
$dt->addLabelColumn($this->locale->_('locale_short_date_format'), '{short_date_format}');
$dt->addLabelColumn($this->locale->_('locale_long_date_format'), '{long_date_format}');
$dt->addLinkColumn('', $this->locale->_('edit'), urldecode($this->router->buildUrl('edit', array('id'=>'{id}'))), 'small', 'small');
$dt->addLinkColumn('', $this->locale->_('locale_dictionary'), urldecode($this->router->buildUrl('dictionary/list', array('languageid'=>'{id}'))), 'small', 'small');
$this->assign('datatable', $dt);

$p = $this->html->pagination($this->total, PAGINATION_SIZE, PAGINATION_RANGE);
$this->assign('pagination', $p);

echo $this->includeTemplate('layout\list');