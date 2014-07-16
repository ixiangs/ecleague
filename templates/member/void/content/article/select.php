<?php $this->beginBlock('content'); ?>
<form id="form1" method="get">
<?php
$dt = $this->html->grid($this->models);
$dt->addLabelColumn($this->localize->_('title'), '@{title}', '', 'left')
    ->setFilter($this->html->textbox('title', 'title', $this->request->getQuery('title')));
$dt->addLabelColumn($this->localize->_('category'), '@{category_name}', 'middle', 'left')
    ->setFilter($this->html->select('categoryid', 'categoryid', $this->request->getQuery('categoryid', null), $this->categories)
        ->setCaption(''))
    ->setCellRenderer(function($column, $row, $index){
        $cname = $row['category_name'];
        return '<td>'.($cname? $cname: $this->localize->_('content_uncategory')).'</td>';
    });
$dt->addButtonColumn('', $this->localize->_('select'), "@selectArticle('{id}', '{title}')", 'edit', 'edit');
echo $dt->render();

echo $this->html->pagination($this->total, PAGINATION_SIZE, PAGINATION_RANGE)
        ->setAttribute('class', 'pagination pull-right')
        ->render();
$this->endBlock();
?>
</form>
<?php $this->beginScript('article_select'); ?>
<script language="javascript">
    $('title').addEvent('keyup', function(e){
        if(e.key == 'enter'){
            $('form1').submit();
        }
    });
    $('categoryid').addEvent('change', function(){
        $('form1').submit();
    });
    function selectArticle(aid, atitle){
        window.parent.createArticleLink(aid, atitle);
    }
</script>
<?php
$this->endScript();
echo $this->includeTemplate('layout\empty');