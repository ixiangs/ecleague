<?php
$this->assign('toolbar', array(
    $this->html->anchor($this->localize->_('new'), $this->router->buildUrl('add'))
));

$dt = $this->html->grid($this->models);
$dt->addLabelColumn($this->localize->_('title'), '@{title}', '', 'left');
$dt->addStatusColumn($this->localize->_('status'), '@{status}', array(
        \Components\Content\Constant::STATUS_ARTICLE_PUBLISHED=>'<span class="label label-success">'.$this->localize->_('content_status_published').'</span>',
        \Components\Content\Constant::STATUS_ARTICLE_UNPUBLISHED=>'<span class="label label-warning">'.$this->localize->_('content_status_unpublished').'</span>',
        \Components\Content\Constant::STATUS_ARTICLE_DISABLED=>'<span class="label label-danger">'.$this->localize->_('content_status_disabled').'</span>'),
    'small', 'small text-center');
$dt->addButtonColumn('', $this->localize->_('delete'), "@deleteConfirm('".urldecode($this->router->buildUrl('delete', array('id'=>'{id}')))."')", 'edit', 'edit');
$this->assign('datatable', $dt);

$p = $this->html->pagination($this->total, PAGINATION_SIZE, PAGINATION_RANGE);
$this->assign('pagination', $p);

echo $this->includeTemplate('layout\list');