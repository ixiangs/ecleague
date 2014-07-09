<?php
$this->assign('toolbar', array(
    $this->html->anchor($this->localize->_('back'), $this->router->buildUrl('list'))
        ->setAttribute('class', 'btn btn-default'),
    $this->html->button('button', $this->localize->_('save'), 'btn btn-primary')->setAttribute('data-submit', 'form1')
));

$f = $this->html->form()
    ->setAttribute('action', $this->router->buildUrl('save', '*'));
$f->newField($this->localize->_('title'), true,
    $this->html->textbox('title', 'data[title]', $this->model->getName())
        ->addValidateRule('required', true));
$f->newField($this->localize->_('content'), true,
    $this->html->textarea('content', 'data[content]', $this->model->getName())
        ->addValidateRule('required', true))
        ->setLabelVisible(false);
$f->addHidden('id', 'data[id]', $this->model->getId());
$this->assign('form', $f);
\Toy\Html\Document::singleton()
    ->addReferenceCss('kindThemes', STATIC_URL . 'kindeditor/themes/default/default.css')
    ->addReferenceScript('kindjs', STATIC_URL . 'kindeditor/kindeditor.js')
    ->addReferenceScript('kindlang', STATIC_URL . 'kindeditor/lang/zh_CN.js');
$this->beginScript('account');
?>
    <script language="JavaScript">
        KindEditor.ready(function (K) {
            window.editor = K.create('#content');
        });
    </script>
<?php
$this->endScript();
echo $this->includeTemplate('layout\form');