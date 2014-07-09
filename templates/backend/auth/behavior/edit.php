<?php
$this->assign('breadcrumb', array(
    $this->html->anchor($this->localize->_('auth_manage')),
    $this->html->anchor($this->localize->_($this->router->action == 'add' ? "user_new_behavior" : "user_edit_behavior"))
));

$this->assign('toolbar', array(
    $this->html->anchor($this->localize->_('back'), $this->router->buildUrl('list'))
        ->setAttribute('class', 'btn btn-default'),
    $this->html->button('button', $this->localize->_('save'), 'btn btn-primary')->setAttribute('data-submit', 'form1')
));

$f = $this->html->form()
    ->setAttribute('action', $this->router->buildUrl('save', '*'));
$f->newField($this->localize->_('component'), true,
    $this->html->select('component_id', 'data[component_id]', $this->model->getComponentId(), $this->components)
        ->setCaption('')
        ->addValidateRule('required', true));
if ($this->model->getId()):
    $f->addStaticField($this->localize->_('code'), $this->model->getCode());
else:
    $f->newField($this->localize->_('code'), true,
        $this->html->textbox('code', 'data[code]', $this->model->getCode())
            ->addValidateRule('required', true));
endif;
$f->newField($this->localize->_('name'), true,
    $this->html->textbox('name', 'data[name]', $this->model->getName())
        ->addValidateRule('required', true));

$f->newField($this->localize->_('enable'), true,
    $this->html->select('enabled', 'data[enabled]', $this->model->getEnabled(), array(
        '0' => $this->localize->_('no'),
        '1' => $this->localize->_('yes')
    )));
$f->addHidden('id', 'data[id]', $this->model->getId());
$this->assign('form', $f);
echo $this->includeTemplate('layout\form');