<?php
$this->assign('breadcrumb', array(
    $this->html->anchor($this->locale->_('attrs_manage')),
    $this->html->anchor($this->locale->_('attrs_new_attribute_set'))
));

$this->assign('navigationBar', array(
    $this->html->anchor($this->locale->_('back'), $this->router->buildUrl('list'))
));

$this->assign('toolbar', array(
    $this->html->button('button', $this->locale->_($this->model->id? 'save': 'next_step'), 'btn btn-primary')
        ->setAttribute('data-submit', 'form1')
));

$f = $this->html->groupedForm()
        ->setAttribute('action', $this->router->buildUrl('save', '*'));
$f->beginGroup('tab_base', $this->locale->_('base_info'));
if($this->model->id):
    $f->addStaticField($this->locale->_('attrs_owner_component'), $this->components[$this->model->getComponentId()]);
else:
    $f->newField($this->locale->_('attrs_owner_component'), true,
        $this->html->select('component_id', 'component_id', $this->request->getQuery('component_id'), $this->components)
            ->setCaption('')
            ->addValidateRule('required', true));
endif;
$f->newField($this->locale->_('code'), true,
    $this->html->textbox('code', 'data[code]', $this->model->getCode())
        ->addValidateRule('required', true));
$f->newField($this->locale->_('enable'), true,
    $this->html->select('enabled', 'data[enabled]', $this->model->getEnabled(), array('1'=>$this->locale->_('yes'), '0'=>$this->locale->_('no')))
        ->addValidateRule('required', true));
$f->endGroup();


foreach($this->locale->getLanguages() as $lang):
    $f->beginGroup('tab_lang_'.$lang['code'], $lang['name']);
    $f->newField($this->locale->_('name'), true,
        $this->html->textbox('name_'.$lang['id'], 'data[name]['.$lang['id'].']', $this->model->name[$lang['id']])
            ->addValidateRule('required', true));
    $f->newField($this->locale->_('memo'), true,
        $this->html->textbox('memo_'.$lang['id'], 'data[memo]['.$lang['id'].']', $this->model->memo[$lang['id']])
            ->addValidateRule('required', true));
    $f->endGroup();
endforeach;

$f->addHidden('id', 'data[id]', $this->model->getId());
$this->assign('form', $f);
echo $this->includeTemplate('layout\form');