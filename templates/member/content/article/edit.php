<?php
$this->assign('toolbar', array(
    $this->html->anchor($this->localize->_('back'), $this->router->buildUrl('list'))
        ->setAttribute('class', 'btn btn-default'),
    $this->html->button('button', $this->localize->_('save'), 'btn btn-primary')->setAttribute('data-submit', 'form1')
));

$f = $this->html->form();
$f->newField($this->localize->_('title'), true,
    $this->html->textbox('title', 'data[title]', $this->model->getTitle())
        ->addValidateRule('required', true));
$f->newField($this->localize->_('category'), true,
    $this->html->select('category_id', 'data[category_id]', $this->model->getCategoryId(), $this->categories)
        ->addValidateRule('required', true));
$f->newField($this->localize->_('content'), true,
    $this->html->textarea('content', 'data[content]', $this->model->getContent())
        ->addValidateRule('required', true))
    ->setLabelVisible(false);
$f->addHidden('id', 'data[id]', $this->model->getId());
$f->addHidden('directory', 'data[directory]', $this->model->getDirectory());
$this->assign('form', $f);
\Toy\Html\Document::singleton()
    ->addReferenceCss('kindThemes', STATIC_URL . 'kindeditor/themes/default/default.css')
    ->addReferenceScript('kindjs', STATIC_URL . 'kindeditor/kindeditor.js')
    ->addReferenceScript('kindlang', STATIC_URL . 'kindeditor/lang/zh_CN.js');
$this->beginScript('account');
?>
    <script language="JavaScript">
        KindEditor.ready(function (K) {
            window.editor = K.create('#content', {
                uploadJson: '<?php echo $this->router->buildUrl('upload', array('directory'=>$this->model->getDirectory())); ?>'
            });
        });
        window.addEvent('domready', function () {
            $('form1').retrieve('validator').addEvent('before', function () {
                window.editor.sync();
            });
        });
    </script>
<?php
$this->endScript();
echo $this->includeTemplate('layout\form');