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
$this->assign('form', $f);

$this->beginBlock('footerjs');
?>
    <script language="javascript">
        var curIndex = <?php echo count($this->options); ?>;
        var lineHtml = '<div class="panel panel-default"><div class="panel-heading"><?php echo $this->locale->_('dass_option'); ?></div><div class="panel-body">'+
            '<div class="form-group">' +
            '<label class="col-sm-1 control-label" for="options_{index}_value"><?php echo $this->locale->_('dass_option_value'); ?></label>' +
            '<div class="col-sm-9"><input type="text" value="" data-validate-required="true" id="options_{index}_value" name="options[{index}][value]" class="form-control">' +
            '</div></div>';
        <?php foreach($this->locale->getLanguages() as $lang): ?>
        lineHtml += '<div class="form-group">' +
            '<label class="col-sm-1 control-label" for="options_{index}_<?php $lang['id']?>"><?php echo $lang['name']; ?></label>' +
            '<div class="col-sm-9"><input type="text" value="" data-validate-required="true" id="options_{index}_<?php echo $lang['id']?>" name="options[{index}][langs][<?php echo $lang['id']?>]" class="form-control">' +
            '</div></div>';
        <?php endforeach; ?>
        lineHtml += '</div></div>';
        lineHtml += '<input type="hidden" name="options[{index}][id]" value="{id}"/>';

        $('#new').click(function () {
            $(lineHtml.substitute({'index': curIndex, 'id':''})).appendTo('#form1');
            curIndex++;
        });

        function deleteField(index){

        }
    </script>
<?php
$this->endBlock();

echo $this->includeTemplate('layout\form');