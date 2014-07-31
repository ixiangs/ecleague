<?php
$toolbarArr = array(
    $this->html->anchor($this->localize->_('back'), $this->router->buildUrl('list'))
        ->setAttribute('class', 'btn btn-default')
);

$this->assign('toolbar', $toolbarArr);

$this->beginBlock('form');
?>
<?php foreach($this->types as $k=>$v):?>
<div class="col-md-3">
 <a href="<?php echo $this->router->buildUrl('add', array('type'=>$k)); ?>"><?php echo $v; ?></a>
</div>
<?php endforeach; ?>
    <div class="clearfix">&nbsp;</div>
<?php
$this->endBlock();
echo $this->includeTemplate('layout\form');