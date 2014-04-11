<?php
$this->assign('breadcrumb', array(
    $this->html->anchor($this->locale->_('dass_manage')),
    $this->html->anchor($this->attribute->getName()),
    $this->html->anchor($this->locale->_('dass_option'))
));

$this->assign('navigationBar', array(
    $this->html->anchor($this->locale->_('back'), $this->router->buildUrl('list'))
));

$this->assign('toolbar', array(
    $this->html->button('button', $this->locale->_('new'), 'btn btn-success')->setAttribute('id', 'new'),
    $this->html->button('button', $this->locale->_('save'), 'btn btn-primary')->setAttribute('data-submit', 'form1')
));

$f = $this->html->form();
$f->setAttribute('data-validate', 'true')->addHiddenField('attribute_id', 'attribute_id', $this->attribute->getId());
$this->assign('form', $f);

$this->beginBlock('footerjs');
?>
    <script language="javascript">
        var curIndex = 0;
        var optionHtml = '<div class="panel panel-default"><div class="panel-heading"><?php echo $this->locale->_('dass_option'); ?></div><div class="panel-body">'+
            '<div class="form-group">' +
            '<label class="col-sm-1 control-label" for="options_{index}_value"><?php echo $this->locale->_('dass_option_value'); ?></label>' +
            '<div class="col-sm-9"><input type="text" value="{ovalue}" data-validate-required="true" id="options_{index}_value" name="options[{index}][value]" class="form-control">' +
            '</div></div>';
        <?php foreach($this->locale->getLanguages() as $lang): ?>
        optionHtml += '<div class="form-group">' +
            '<label class="col-sm-1 control-label" for="options_{index}_<?php $lang['id']?>"><?php echo $lang['name']; ?></label>' +
            '<div class="col-sm-9"><input type="text" value="{olabel<?php echo $lang['id']; ?>}" data-validate-required="true" id="options_{index}_label_<?php echo $lang['id']?>" name="options[{index}][labels][<?php echo $lang['id']?>]" class="form-control" data-validate-required="true">' +
            '</div></div>';
        <?php endforeach; ?>
        optionHtml += '<div class="form-group"><div class="col-sm-1"></div><div class="col-sm-9">' +
            '<button type="button" class="btn btn-danger" id="delete_{index}" onclick="javascript:deleteField({id});"><?php echo $this->locale->_('delete'); ?></button>' +
        '</div></div>';
        optionHtml += '</div></div>';
        optionHtml += '<input type="hidden" name="options[{index}][id]" value="{id}"/>';

        $('#new').click(function () {
            newOption({});
            $('#form1').data('validator').reload();
        });

        function newOption(data){
            data['index'] = curIndex;
            $(optionHtml.substitute(data)).appendTo('#form1');
            curIndex++;
        }

        function deleteOption(index){

        }


        <?php if(count($this->options) > 0): ?>
        $(function(){
        <?php foreach($this->options as $index=>$option): ?>
            var data<?php echo $index; ?> = {'id':'<?php echo $option->getId(); ?>',
                                             'ovalue':'<?php echo $option->getValue(); ?>'};
            <?php foreach($option->getLabels() as $lid=>$label): ?>
            data<?php echo $index; ?>['<?php echo 'olabel'. $lid?>'] = '<?php echo $label; ?>';
            <?php endforeach; ?>
            newOption(data<?php echo $index; ?>);
        <?php endforeach; ?>
        });
        <?php endif; ?>
    </script>
<?php
$this->endBlock();

echo $this->includeTemplate('layout\form');