<?php $this->beginBlock('content'); ?>
<ul class="breadcrumb">
    <li>
        <a href="#"><?php echo $this->locale->_("auth_manage"); ?></a>
    </li>
    <li>
        <a href="<?php echo $this->router->buildUrl('list'); ?>"><?php echo $this->locale->_("auth_behavior_list") ;?></a>
    </li>
    <li class="active">
        <?php echo $this->locale->_($this->router->action == 'add'? "add":"edit") ;?>
    </li>
</ul>

<div class="col-md-4">
    <?php echo $this->html->beginForm('form1'); ?>
        <?php echo $this->html->field($this->locale->_('code'), $this->html->Input('text', 'code', 'code', 'form-control', $this->model->getCode(), array('data-validate-required'=>'true', 'data-validate-character'=>'true')));?>
    <?php echo $this->html->field($this->locale->_('name'), $this->html->Input('text', 'name', 'name', 'form-control', $this->model->getName(), array('data-validate-required'=>'true')));?>
    <?php echo $this->html->field($this->locale->_('url'), $this->html->Input('text', 'url', 'url', 'form-control', $this->model->getUrl()));?>
    <?php echo $this->html->field($this->locale->_('enable'), $this->html->select('', array(
        0=>$this->locale->no,
        1=>$this->locale->yes
    ), 'enable', 'enable', 'form-control', $this->model->getEnabled(), array('data-validate-required'=>'true', 'data-validate-character'=>'true')));?>
        <div class="form-group">
            <button type="submit" class="btn btn-default btn-primary"><?php echo $this->locale->_("save") ;?></button>
            <a href="" class="btn btn-default"><?php echo $this->locale->_("back") ;?></a>
        </div>
        <input type="hidden" id="id" name="id" value="<?php echo $this->model->getId(); ?>"/>
    <?php echo $this->html->endForm(); ?>
</div>
<?php $this->nextBlock('footerjs'); ?>
<script type="text/javascript">
    var validator = new Toys.Html.Validation.Validator('#form1');
</script>
<?php
$this->endBlock();
echo $this->includeTemplate('master');