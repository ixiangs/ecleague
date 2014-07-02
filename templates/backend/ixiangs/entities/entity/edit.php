<?php
$this->assign('navigationBar', array(
    $this->html->anchor($this->localize->_('back'), $this->router->buildUrl('list'))
));

$this->assign('toolbar', array(
    $this->html->button('button', $this->localize->_($this->model->id? 'save': 'next_step'), 'btn btn-primary')
        ->setAttribute('data-submit', 'form1')
));

$f = $this->html->form()
        ->setAttribute('action', $this->router->buildUrl('save', '*'));
if($this->model->id):
    $f->addStaticField($this->localize->_('entities_component'), $this->components[$this->model->getComponentId()]);
else:
    $f->newField($this->localize->_('entities_component'), true,
        $this->html->select('component_id', 'data[component_id]', $this->model->getComponentId(), $this->components)
            ->setCaption('')
            ->addValidateRule('required', true));
endif;
$f->newField($this->localize->_('entities_model'), true,
    $this->html->textbox('model', 'data[model]', $this->model->getModel())
        ->addValidateRule('required', true));
$f->newField($this->localize->_('name'), true,
    $this->html->textbox('name', 'data[name]', $this->model->name)
        ->addValidateRule('required', true));
$f->newField($this->localize->_('dbtable'), true,
    $this->html->textbox('table_name', 'data[table_name]', $this->model->table_name)
        ->addValidateRule('required', true));
$f->newField($this->localize->_('memo'), true,
    $this->html->textbox('memo', 'data[memo]', $this->model->memo));
$f->newField($this->localize->_('enable'), true,
    $this->html->select('enabled', 'data[enabled]', $this->model->getEnabled(), array('1'=>$this->localize->_('yes'), '0'=>$this->localize->_('no')))
        ->addValidateRule('required', true));


$f->addHidden('id', 'data[id]', $this->model->getId());
$this->assign('form', $f);
echo $this->includeTemplate('layout\form');