<?php
//$selectedCategory = 0;
//if($this->model->getLink()){
//    parse_str(parse_url($this->model->getLink(), PHP_URL_QUERY), $arr);
//    $selectedCategory = $arr['categoryid'];
//}
$this->form->newField($this->localize->_('content_single_article'), true,
    $this->html->textbox('single_article', '', '')
        ->setRightAddon($this->html->button('button', $this->localize->_('weiweb_select'))
            ->setAttribute('id', 'select_article')));

$this->beginBlock('other');
?>
<div id="article_list_modal" class="modal hide" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
<h4 class="modal-title">Modal title</h4>
</div>
<div class="modal-body">
<p>One fine body…</p>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
<button type="button" class="btn btn-primary">Save changes</button>
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
        $('link').set('readonly', 'readonly');
//        $('article_category').addEvent('change', function () {
//            var selected = this.getSelected();
//            if (selected[0].get('value') == '') {
//                $('link').set('value', '');
//            } else {
//                $('link').set('value', 'void_content/article/list?categoryid=' + selected[0].get('value'));
//            }
//        });
    </script>
<?php
$this->endScript();