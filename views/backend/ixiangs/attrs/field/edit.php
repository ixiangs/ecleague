<?php
$this->assign('navigationBar', array(
    $this->html->anchor($this->locale->_('back'), $this->router->buildUrl('list'))
));

$this->assign('toolbar', array(
    $this->html->button('button', $this->locale->_('save'), 'btn btn-primary')
            ->setAttribute('data-submit', 'form1')
));

$f = $this->html->form()
        ->setAttribute('action', $this->router->buildUrl('save', '*'));

$f->newField($this->locale->_('text'), true,
    $this->html->textbox('label', 'data[labe]', $this->model->getLabel())
        ->addValidateRule('required', true));
$f->newField($this->locale->_('attrs_indexable'), true,
    $this->html->select('indexable', 'data[indexable]', $this->model->getIndexable(),
        array('1'=>$this->locale->_('yes'), '0'=>$this->locale->_('no'))));

$f->addHidden('id', 'data[id]', $this->model->getId());

$this->assign('form', $f);
echo $this->includeTemplate('layout\form');