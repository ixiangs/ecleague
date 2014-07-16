<?php
$articleTitle = '';
if($this->model->getLink()){
    parse_str(parse_url($this->model->getLink(), PHP_URL_QUERY), $arr);
    $articleId = $arr['id'];
    $articleTitle = \Void\Content\ArticleModel::load($articleId)->getTitle();
}

$this->form->newField($this->localize->_('content_single_article'), true,
    $this->html->textbox('single_article', '', $articleTitle)
        ->setRightAddon($this->html->button('button', $this->localize->_('weiweb_select'))
            ->setAttribute('id', 'select_article')));

$this->beginBlock('other');
?>
<div id="article_list_modal" class="modal hide" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
<div class="modal-dialog modal-lg">
<div class="modal-content">
<div class="modal-body">
<iframe style="margin: 0;padding: 0;border: none;width:100%;height: 450px;" src="<?php echo $this->router->buildUrl('void_content/article/select');?>"></iframe>
</div>
</div>
</div>
</div>
<?php
$this->endBlock();

$this->beginScript('content_menutype');
?>
    <script language="javascript">
        var articleModal = new Toy.Widget.Modal('article_list_modal');
        $('select_article').addEvent('click', function(){
           articleModal.show();
        });
        function createArticleLink(aid, atitle){
            $('link').set('value', 'void_content/article/detail?id=' + aid);
            $('single_article').set('value', atitle);
            articleModal.hide();
        }
        $('link').set('readonly', 'readonly');

    </script>
<?php
$this->endScript();