<?php
$this->assign('breadcrumb', array(
    $this->html->anchor($this->locale->_('user_manage')),
    $this->html->anchor($this->locale->_($this->router->action == 'add' ? "user_new_behavior" : "user_edit_behavior"))
));

$this->assign('navigationBar', array(
    $this->html->anchor($this->locale->_('back'), $this->router->buildUrl('list'))
));

$this->assign('toolbar', array(
    $this->html->button('button', $this->locale->_('save'), 'btn btn-primary')->setAttribute('data-submit', 'form1')
));

$f = $this->html->form()
    ->setAttribute('action', $this->router->buildUrl('save', '*'));
$f->newField($this->locale->_('owner_component'), true,
    $this->html->select('component_id', 'data[component_id]', $this->model->getComponentId(), $this->components)
        ->setCaption('')
        ->addValidateRule('required', true));
if ($this->model->getId()):
    $f->addStaticField($this->locale->_('code'), $this->model->getCode());
else:
    $f->newField($this->locale->_('code'), true,
        $this->html->textbox('code', 'data[code]', $this->model->getCode())
            ->addValidateRule('required', true));
endif;
$f->newField($this->locale->_('name'), true,
    $this->html->textbox('name', 'data[name]', $this->model->getName())
        ->addValidateRule('required', true));

$f->newField($this->locale->_('enable'), true,
    $this->html->select('enabled', 'data[enabled]', $this->model->getEnabled(), array(
        '0' => $this->locale->_('no'),
        '1' => $this->locale->_('yes')
    )));
$f->addHidden('id', 'data[id]', $this->model->getId());
$this->assign('form', $f);
echo $this->includeTemplate('layout\form');