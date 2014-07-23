<?php
$toolbarArr = array(
    $this->html->button('button', $this->localize->_('save'), 'btn btn-primary')->setAttribute('data-submit', 'form1')
);
$this->assign('toolbar', $toolbarArr);

$f = $this->html->form();
$this->assign('form', $f);

$f->newField($this->localize->_('title'), true,
    $this->html->textbox('title', 'data[title]', $this->model->getTitle())
        ->addValidateRule('required', true));
$f->newField($this->localize->_('weiweb_background_color'), false,
    $this->html->textbox('background_color', 'data[background_color]', $this->model->getBackgroundColor()));
$f->newField($this->localize->_('weiweb_background_image'), false,
    $this->html->IframeInput('background_image', 'data[background_image]', $this->model->getIcon())
        ->setIframeUrl($this->router->buildUrl('image', array('id'=>$this->model->getId())))
        ->setIframeClass('upload-iframe'));
$f->addHidden('id', 'data[id]', $this->model->getId());
echo $this->includeTemplate('layout\form');