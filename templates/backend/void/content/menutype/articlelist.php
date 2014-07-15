<?php
$articleCategories = \Void\Content\CategoryModel::find()
    ->eq('account_id', $this->identity->getId())
    ->fetch()
    ->combineColumns('id', 'name');
$selectedCategory = 0;
if($this->model->getLink()){
    parse_str(parse_url($this->model->getLink(), PHP_URL_QUERY), $arr);
    $selectedCategory = $arr['categoryid'];
}
$this->form->newField($this->localize->_('content_categories'), true,
    $this->html->select('article_category', '', $selectedCategory, $articleCategories)
        ->setCaption(''));
$this->beginScript('content_menutype');
?>
    <script language="javascript">
        $('link').set('readonly', 'readonly');
        $('article_category').addEvent('change', function () {
            var selected = this.getSelected();
            if (selected[0].get('value') == '') {
                $('link').set('value', '');
            } else {
                $('link').set('value', 'void_content/article/list?categoryid=' + selected[0].get('value'));
            }
        });
    </script>
<?php
$this->endScript();