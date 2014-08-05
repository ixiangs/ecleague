<?php
$toolbarArr = array(
    $this->html->anchor($this->localize->_('back'), $this->router->buildUrl('list'))
        ->setAttribute('class', 'btn btn-default'),
    $this->html->button('button', $this->localize->_('delete'), 'btn btn-danger')
        ->setEvent('click', "deleteConfirm('".$this->router->buildUrl('delete', array('id'=>$this->model->getId()))."')"),
    $this->html->button('button', $this->localize->_('save'), 'btn btn-primary')->setAttribute('data-submit', 'form1')
);
if($this->router->action == 'add'):
    unset($toolbarArr[1]);
endif;

$this->assign('toolbar', $toolbarArr);

$f = $this->html->form();
$f->newField($this->localize->_('title'), true,
    $this->html->textbox('title', 'data[title]', $this->model->getTitle())
        ->addValidateRule('required', true));
$f->newField($this->localize->_('category'), true,
    $this->html->select('category_id', 'data[category_id]', $this->model->getCategoryId(), $this->categories)
        ->addValidateRule('required', true));
$f->newField($this->localize->_('content_intro_image'), false,
    $this->html->IframeInput('intro_image', 'data[intro_image]', $this->model->getIntroImage())
        ->setIframeUrl($this->router->buildUrl('images',
            array('id'=>$this->model->getId(), 'type'=>'intro', 'directory'=>$this->model->getDirectory())))
        ->setIframeClass('upload-iframe'));
$f->newField($this->localize->_('content_article_image'), false,
    $this->html->IframeInput('article_image', 'data[article_image]', $this->model->getArticleImage())
        ->setIframeUrl($this->router->buildUrl('images',
            array('id'=>$this->model->getId(), 'type'=>'article', 'directory'=>$this->model->getDirectory())))
        ->setIframeClass('upload-iframe'));
$f->newField($this->localize->_('status'), true,
    $this->html->select('status', 'data[status]', $this->model->getStatus(), array(
        VOID_CONTENT_STATUS_ARTICLE_PUBLISHED => $this->localize->_('content_status_published'),
        VOID_CONTENT_STATUS_ARTICLE_UNPUBLISHED => $this->localize->_('content_status_unpublished')
    )));
$f->newField($this->localize->_('content_introduction'), false,
    $this->html->textarea('introduction', 'data[introduction]', $this->model->getIntroduction())
        ->setAttribute('style', 'height:60px'));
$f->newField($this->localize->_('content_content'), true,
    $this->html->textarea('content', 'data[content]', $this->model->getContent())
        ->addValidateRule('required', true));
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
                allowFlashUpload: false,
                allowMediaUpload: false,
                filePostName: 'uploadfile',
                height: 450,
                uploadJson: '<?php echo $this->router->buildUrl('upload',
                    array('id'=>$this->model->getId(),
                            'directory'=>$this->model->getDirectory(),
                            'type'=>'editor')); ?>',
                items:[
                    'source', '|', 'undo', 'redo', '|', 'preview', 'cut', 'copy', 'paste',
                    'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
                    'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
                    'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
                    'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
                    'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image',
                    'table', 'hr', 'emoticons', 'baidumap', 'pagebreak',
                    'anchor', 'link', 'unlink'
                ]
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