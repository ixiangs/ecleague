<?php
$this->assign('navigationBar', array(
    $this->html->anchor($this->locale->_('back'), $this->router->buildUrl('list'))
));

$this->assign('toolbar', array(
    $this->html->button('button', $this->locale->_($this->model->id? 'save': 'next_step'), 'btn btn-primary')
        ->setAttribute('data-submit', 'form1')
));

$f = $this->html->form()
        ->setAttribute('action', $this->router->buildUrl('save', '*'));
if($this->model->id):
    $f->addStaticField($this->locale->_('entities_component'), $this->components[$this->model->getComponentId()]);
else:
    $f->newField($this->locale->_('entities_component'), true,
        $this->html->select('component_id', 'data[component_id]', $this->model->getComponentId(), $this->components)
            ->setCaption('')
            ->addValidateRule('required', true));
endif;
$f->newField($this->locale->_('entities_model'), true,
    $this->html->textbox('model', 'data[model]', $this->model->getModel())
        ->addValidateRule('required', true));
$f->newField($this->locale->_('name'), true,
    $this->html->textbox('name', 'data[name]', $this->model->name)
        ->addValidateRule('required', true));
$f->newField($this->locale->_('memo'), true,
    $this->html->textbox('memo', 'data[memo]', $this->model->memo));
$f->newField($this->locale->_('enable'), true,
    $this->html->select('enabled', 'data[enabled]', $this->model->getEnabled(), array('1'=>$this->locale->_('yes'), '0'=>$this->locale->_('no')))
        ->addValidateRule('required', true));


$f->addHidden('id', 'data[id]', $this->model->getId());
$this->assign('form', $f);
echo $this->includeTemplate('layout\form');