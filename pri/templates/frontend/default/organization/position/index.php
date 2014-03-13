<?php $this->beginBlock('main'); ?>
<nav class="navbar navbar-default" role="navigation">
	<div class="navbar-header">
		<ol class="breadcrumb">
		  <li><?php echo $this->languages['organisation']; ?></li>
		  <li class="active"><?php echo $this->languages['organization_position_list']; ?></li>
		</ol>
  </div>
  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul class="nav navbar-nav navbar-right">
      <li><a href="<?php echo $this->router->buildUrl('add'); ?>"><?php echo $this->languages['add']; ?></a></li>
    </ul>
  </div>
</nav>
<table class="table table-hover">
<thead>
<tr>
<th class="index">#</th>
<th><?php echo $this->languages['chinese_name']?></th>
<th><?php echo $this->languages['english_name']; ?></th>
<th><?php echo $this->languages['department']; ?></th>
<th class="edit"></th>
</tr>
</thead>
<tbody>
<?php foreach($this->models as $i=>$model): ?>
<tr>
<td class="index"><?php echo $i+1; ?></td>
<td style="text-align:right;"><?php echo $model->getChineseName(); ?></td>
<td><?php echo $model->getEnglishName(); ?></td>
<td><?php echo $this->departments[$model->getDepartmentId()]; ?></td>
<td class="edit">
	<a href="<?php echo $this->router->buildUrl('edit', array('id'=>$model->getId())); ?>"><?php echo $this->languages['edit']; ?></a>
	<a href="<?php echo $this->deleteConfirm($this->router->buildUrl('delete', array('id'=>$model->getId()))); ?>"><?php echo $this->languages['delete']; ?></a>	
</td>
</tr>
<?php endforeach; ?>	
<?php 
// $treeItems = array();
// function recursiveTreeItem(&$treeItem, $models){
	// foreach($models as $model){
		// if($model->getParentId() == $treeItem['model']->getId()){
			// $newItem = array('level'=>$treeItem['level'] + 1, 'model'=>$model, 'children'=>array());
			// recursiveTreeItem($newItem, $models);
			// $treeItem['children'][] = $newItem;
		// }
	// }
// }
// foreach($this->models as $i=>$model):
	// if($model->getParentId() == 0):
		// $item = array('level'=>1, 'model'=>$model, 'children'=>array());
		// recursiveTreeItem($item, $this->models);
		// $treeItems[] = $item;
	// endif;
// endforeach;
// 
// function renderTreeRow($titem, $tmpl, $router, $langs){
	// $html = array('<tr>');
	// $html[] = '<td style="padding-left:'.($titem['level'] * 20).'px">'.$titem['model']->getChineseName().'/'.$titem['model']->getEnglishName().'</td>';
	// foreach($tmpl->departments as $d){
		// if($titem['model']->getDepartmentId() == $d->getId()){
			// $html[] = '<td>'.$d->getName().'</td>';
		// }
	// }
// 	
	// $html[] = '<td class="edit">';
	// $html[] = '<a href="'.$router->buildUrl('edit', array('id'=>$titem['model']->getId())).'">'.$langs['edit'].'</a>';
	// // $html[] = '<a href="'.$tmpl->deleteConfirm($this->router->buildUrl('delete', array('id'=>$titem['model']->getId()))).'">'.$tmpl->langs['delete'].'</a>';	
	// $html[] = '</td></tr>';
	// echo implode('', $html);
	// if(!empty($titem['children'])){
		// foreach($titem['children'] as $citem){
			// renderTreeRow($citem, $tmpl, $router, $langs);
		// }
	// }
// }
// 
// foreach($treeItems as $titem):
	// renderTreeRow($titem, $this, $this->router, $this->languages);
// endforeach;
?>
</tbady>
</table>
<div class="pull-right">
<?php echo $this->includeTemplate('pagination'); ?>
</div>
<?php $this->endBlock(); ?>
<?php echo $this->includeTemplate('master'); ?>
