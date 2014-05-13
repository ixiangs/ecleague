<?php
$this->assign('navigationBar', array(
    $this->html->anchor($this->locale->_('back'), $this->router->buildUrl('list'))
));

$this->assign('toolbar', array(
    $this->html->button('button', $this->locale->_('save'), 'btn btn-primary')->setAttribute('data-submit', 'form1')
));

$f = $this->html->groupedForm()
    ->setAttribute('action', $this->router->buildUrl('save', '*'));
$f->beginGroup('tab_personal', $this->locale->_('personal_info'));
$f->newField($this->locale->_('office_department'), true,
    $this->html->select('department_id', 'data[department_id]', $this->model->getDepartmentId(), $this->departments)
        ->setCaption($this->locale->_('office_department'))
        ->addValidateRule('required', true));
$f->newField($this->locale->_('office_position'), true,
    $this->html->treeSelect('position_id', 'data[position_id]', $this->model->getPositionId(), $this->positions)
        ->setCaption($this->locale->_('office_position'))
        ->addValidateRule('required', true));
$f->newField($this->locale->_('name'), true,
    $this->html->textbox('name', 'data[name]', $this->model->getName())
        ->addValidateRule('required', true));
$f->newField($this->locale->_('gender'), true,
    $this->html->select('gender', 'data[gender]', $this->model->getGender(), array(
        1 => $this->locale->_('male'), 2 => $this->locale->_('female')
    ))->setCaption($this->locale->_('gender'))
        ->addValidateRule('required', true));
$f->newField($this->locale->_('birthdate'), true,
    $this->html->textbox('birthdate', 'data[birthdate]', $this->model->getBirthdate(), 'date')
        ->addValidateRule('required', true));
$f->newField($this->locale->_('marital'), true,
    $this->html->select('marital', 'data[marital]', $this->model->getMarital(), array(
        '1' => $this->locale->_('spinsterhood'),
        '2' => $this->locale->_('married')
    ))
        ->addValidateRule('required', true));
$f->newField($this->locale->_('introduction'), true,
    $this->html->textarea('introduction', 'data[introduction]', $this->model->getIntroduction())
        ->setAttribute('rows', 4));
$f->endGroup();
$f->beginGroup('tab_contact', $this->locale->_('contact_info'));
$f->newField($this->locale->_('personal_email'), true,
    $this->html->textbox('personal_email', 'data[personal_email]', $this->model->getPersonalEmail(), 'email')
        ->addValidateRule('required', true));
$f->newField($this->locale->_('work_email'), true,
    $this->html->textbox('work_email', 'data[work_email]', $this->model->getWorkEmail(), 'email')
        ->addValidateRule('required', true));
$f->newField($this->locale->_('mobile'), true,
    $this->html->textbox('mobile', 'data[mobile]', $this->model->getMobile())
        ->addValidateRule('required', true));
$f->newField($this->locale->_('telephone'), false,
    $this->html->textbox('telephone', 'data[telephone]', $this->model->getTelephone()));
$f->newField('QQ', false,
    $this->html->textbox('qq', 'data[qq]', $this->model->getQq()));
$f->newField('MSN', false,
    $this->html->textbox('msn', 'data[msn]', $this->model->getMsn()));
$f->newField('Skype', false,
    $this->html->textbox('skype', 'data[skype]', $this->model->getSkype()));
$f->endGroup();
$f->addHidden('id', 'data[id]', $this->model->getId());
$this->assign('form', $f);
echo $this->includeTemplate('layout\form');