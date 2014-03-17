<?php $this->beginBlock('content'); ?>
<ul class="breadcrumb">
    <li>
        <a href="#"><?php echo $this->locale->_('auth_manage'); ?></a>
    </li>
    <li class="active">
        <?php echo $this->locale->_('auth_behavior_list'); ?>
    </li>
</ul>

<div class="panel panel-default">
    <div class="panel-heading">
        <?php echo $this->locale->_('behavior_list');?>
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
                    <th><?php echo $this->locale->_('code');?></th>
                    <th><?php echo $this->locale->_('name');?></th>
                    <th><?php echo $this->locale->_('url');?></th>
                    <th><?php echo $this->locale->_('enable');?></th>
                    <th style="text-align:center">#</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach(models as $id=>$model):?>
                <tr>
                    <td class="index"><?php echo $id + 1; ?></td>
                    <td><?php echo $model->code; ?></td>
                    <td><?php echo $model->name; ?></td>
                    <td><?php echo $model->url; ?></td>
                    <td><?php echo $this->locale->_($model->enabled? 'yes': 'no'); ?></td>
                    <td style="text-align:center">
                        <a href="<?php echo $this->router->buildUrl(array('id'=>$model->id), 'edit');?>"><?php echo $this->locale->_('edit');?></a>
                        <a href="javascript:deleteConfirm('<?php echo $this->router->buildUrl(array("id"=>$model->id), "delete");?>');"><?php echo $this->locale->_('delete');?></a>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>


        </div>
    </div>
    <div class="panel-footer pagination-panel">
        <?php echo $this->locale->_('total_records', $this->totals); ?>
        <div class="pull-right">

        </div>
    </div>
</div>
</div>
<?php
$this->endBlock();
echo $this->includeTemplate('master');