<?php
$this->assign('navigationBar', array(
    $this->html->anchor($this->locale->_('back'), $this->router->getHistoryUrl('list'))
));

$this->assign('toolbar', array(
    $this->html->button('button', $this->locale->_('save'), 'btn btn-primary')
        ->setAttribute('data-submit', 'form1')
));

$f = $this->html->form()
    ->setAttribute('action', $this->router->buildUrl('save', '*'));
if ($this->model->id):
    $f->addStaticField($this->locale->_('attrs_attribute'), $this->attributes[$this->model->getAttributeId()]);
else:
    $f->newField($this->locale->_('attrs_attribute'), true,
        $this->html->select('attribute_id', 'data[attribute_id]', $this->model->getAttributeId(), $this->attributes)
            ->setCaption('')
            ->addValidateRule('required', true));
endif;
$f->newField($this->locale->_('attrs_required'), true,
    $this->html->select('required', 'data[required]', $this->model->getRequired(),
        array('1' => $this->locale->_('yes'), '0' => $this->locale->_('no'))));
$f->newField($this->locale->_('attrs_indexable'), true,
    $this->html->select('indexable', 'data[indexable]', $this->model->getIndexable(),
        array('1' => $this->locale->_('yes'), '0' => $this->locale->_('no'))));

$f->addHidden('id', 'data[id]', $this->model->getId());
$f->addHidden('id', 'data[component_id]', $this->model->getComponentId());
$f->addHidden('id', 'data[entity_id]', $this->model->getEntityId());

$this->assign('form', $f);
echo $this->includeTemplate('layout\form');