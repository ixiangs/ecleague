<?php
$this->assign('navigationBar', array(
    $this->html->anchor($this->locale->_('back'), $this->router->getHistoryUrl('language/list')),
    $this->html->anchor($this->locale->_('add'), $this->router->buildUrl('add', array('languageid'=>$this->language->getId())))
));

$this->assign('toolbar', array(
    $this->html->button('button', $this->locale->_('search'), 'btn btn-default')
        ->setEvent('click', "$('#table_form').attr('method', 'get').submit();"),
    $this->html->button('button', $this->locale->_('delete'), 'btn btn-danger')
        ->setEvent('click', "deleteSelectedRow('table1', '".$this->router->buildUrl('delete', array('languageid'=>$this->language->getId()))."')")
));

$dt = $this->html->grid($this->models);
$dt->addSelectableColumn('ids[]', '{id}', null, 'index', 'index');
$dt->addIndexColumn('', 'index', 'index');
$dt->addLabelColumn($this->locale->_('code'), '{code}', 'large', 'left')
    ->setFilter($this->html->textbox('code', 'code', $this->request->getParameter('code')));
$dt->addLabelColumn($this->locale->_('text'), '{label}', '', 'left')
    ->setFilter($this->html->textbox('label', 'label', $this->request->getParameter('label')));
$dt->addLinkColumn('', $this->locale->_('edit'), urldecode($this->router->buildUrl('edit', array('languageid'=>$this->language->getId(), 'id'=>'{id}'))), 'small', 'edit text-center');
$dt->addHidden('languageid', 'languageid', $this->language->getId());
$this->assign('datatable', $dt);

$this->assign('pagination', $this->html->pagination($this->total, PAGINATION_SIZE, PAGINATION_RANGE));

echo $this->includeTemplate('layout\list');