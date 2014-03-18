<?php $this->beginBlock('content'); ?>
<ul class="breadcrumb">
    <li>
        <a href="#"><?php echo $this->locale->_('auth_manage'); ?></a>
    </li>
    <li class="active">
        <?php echo $this->locale->_('auth_role_list'); ?>
    </li>
</ul>

<div class="panel panel-default">
    <div class="panel-heading">
        <?php echo $this->locale->_('auth_role_list');?>
        <div class="pull-right">
            <div class="btn-group">
                <a href="<?php echo $this->router->buildUrl('add'); ?>"><?php echo $this->locale->_('add');?></a>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                <tr>
                    <th class="index">#</th>
                    <th class="middle"><?php echo $this->locale->_('code');?></th>
                    <th class="middle"><?php echo $this->locale->_('name');?></th>
                    <th class="small"><?php echo $this->locale->_('enable');?></th>
                    <th class="middle">#</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($this->models as $id=>$model):?>
                <tr>
                    <td class="index"><?php echo $id + 1; ?></td>
                    <td class="middle"><?php echo $model->code; ?></td>
                    <td class="middle"><?php echo $model->name; ?></td>
                    <td class="small"><?php echo $this->locale->_($model->enabled? 'yes': 'no'); ?></td>
                    <td class="middle">
                        <a href="<?php echo $this->router->buildUrl('edit', array('id'=>$model->id));?>"><?php echo $this->locale->_('edit');?></a>
                        <a href="javascript:deleteConfirm('<?php echo $this->router->buildUrl("delete", array("id"=>$model->id));?>');"><?php echo $this->locale->_('delete');?></a>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <div class="row">
                <div class="col-sm-6 pagination-text">
                    <?php echo $this->locale->_('total_records', $this->total); ?>
                </div>
                <div class="col-sm-6">
                    <?php echo $this->html->pagination($this->total,PAGINATION_SIZE, PAGINATION_RANGE); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$this->endBlock();
echo $this->includeTemplate('master');