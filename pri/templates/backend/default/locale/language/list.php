<?php
$this->assign('breadcrumb', array(
    $this->html->anchor($this->locale->_('locale_manage'))
));

$this->assign('navigationBar', array(
    $this->html->anchor($this->locale->_('locale_add_language'), $this->router->buildUrl('add')),
    $this->html->anchor($this->locale->_('locale_import_dictionary'), $this->router->buildUrl('import'))
));

$dt = $this->html->grid($this->models);
$dt->addIndexColumn('#', 'index', 'index');
$dt->addLabelColumn($this->locale->_('code'), '{code}', 'small', 'small text-center');
$dt->addLabelColumn($this->locale->_('name'), '{name}', 'middle', 'middle text-center');
$dt->addLabelColumn($this->locale->_('locale_timezone'), '{timezone}', 'middle', 'middle text-center');
$dt->addLabelColumn($this->locale->_('locale_currency_code'), '{currency_code}', 'middle', 'middle text-center');
$dt->addLabelColumn($this->locale->_('locale_currency_symbol'), '{currency_symbol}', 'middle', 'middle text-center');
$dt->addLabelColumn($this->locale->_('locale_short_date_format'), '{short_date_format}', '', 'text-center');
$dt->addLabelColumn($this->locale->_('locale_long_date_format'), '{long_date_format}', '', 'text-center');
$dt->addLinkColumn('', $this->locale->_('edit'), urldecode($this->router->buildUrl('edit', array('id' => '{id}'))), 'small', 'small edit');
$dt->addLinkColumn('', $this->locale->_('locale_dictionary'), urldecode($this->router->buildUrl('dictionary/list', array('languageid' => '{id}'))), 'small', 'small edit');
$this->assign('datatable', $dt);

$p = $this->html->pagination($this->total, PAGINATION_SIZE, PAGINATION_RANGE);
$this->assign('pagination', $p);

echo $this->includeTemplate('layout\list');