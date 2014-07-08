<?php
$this->assign('toolbar', array(
    $this->html->anchor($this->localize->_('back'), $this->router->buildUrl('list'))
            ->setAttribute('class', 'btn btn-default'),
    $this->html->button('button', $this->localize->_('save'), 'btn btn-primary')->setAttribute('data-submit', 'form1')
));

$f = $this->html->form()
    ->setAttribute('action', $this->router->buildUrl('save', '*'));
$f->newField($this->localize->_('realty_developer'), true,
    $this->html->select('developer_id', 'data[developer_id]', $this->model->getDeveloperId(), $this->developers));
if($this->router->action == 'add'):
$f->newField($this->localize->_('account'), true,
    $this->html->select('user_id', 'data[user_id]', $this->model->getUserId(), $this->accounts));
endif;

$f->newField($this->localize->_('name'), true,
    $this->html->textbox('name', 'data[name]', $this->model->getName())
        ->addValidateRule('required', true));
$f->newField($this->localize->_('address'), true,
    $this->html->textbox('address', 'data[address]', $this->model->getAddress())
        ->addValidateRule('required', true));
$f->addHidden('id', 'data[id]', $this->model->getId());
$this->assign('form', $f);
echo $this->includeTemplate('layout\form');