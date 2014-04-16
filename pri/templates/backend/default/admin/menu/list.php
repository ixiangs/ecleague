<?php
$this->assign('breadcrumb', array(
    $this->html->anchor($this->locale->_('admin_menu_manage'))
));

$this->assign('navigationBar', array(
    $this->html->anchor($this->locale->_('add'), $this->router->buildUrl('add'))
));

$clang = $this->locale->getCurrentLanguage();
$this->beginBlock('datalist');
?>
<div class="row">
<form id="table_form" method="post">
<div class="panel panel-default">
<div class="dd" id="nestable">
<ol class="dd-list">
<?php
$cntMenu = count($this->menus);

for($i = 0; $i < $cntMenu; $i++):
$cMenu = $this->menus[$i];
if($i > 0):
    $pMenu = $this->menus[$i - 1];
else:
    $pMenu = null;
endif;

if($i + 1 >= $cntMenu):
    $nMenu = null;
else:
    $nMenu = $this->menus[$i + 1];
endif;

if(!is_null($pMenu) && $cMenu->level > $pMenu->level):
    echo '<ol class="dd-list">';
endif;
echo '<li class="dd-item" data-id="'.$cMenu->id.'"><div class="dd-handle dd3-handle">drag</div><div class="dd3-content">'.$cMenu->names[$clang['id']].'</div></li>';
if(!is_null($nMenu) && $nMenu->level < $cMenu->level):
    echo '</ol>';
endif;
endfor;
?>
</ol>
</div>
</div>
</form>
</div>
<?php
$this->endBlock();
$this->nextBlock('headcss');
echo '<link href="/pub/assets/css/nestable.css" rel="stylesheet">';
$this->nextBlock('headjs');
echo '<script src="/pub/assets/js/jquery.nestable.js"></script>';
$this->nextBlock('footerjs');
?>
<script>
$(document).ready(function(){
    $('#nestable').nestable();
});
</script>
<?php
$this->endBlock();
echo $this->includeTemplate('layout\list');