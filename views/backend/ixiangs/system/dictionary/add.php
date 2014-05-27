<?php
$this->assign('navigationBar', array(
    $this->html->anchor($this->locale->_('back'), $this->router->getHistoryUrl('list', array('languageid'=>$this->language->getId()))),
));

$this->assign('toolbar', array(
    $this->html->button('button', $this->locale->_('add'), 'btn btn-success')->setAttribute('id', 'add'),
    $this->html->button('button', $this->locale->_('save'), 'btn btn-primary')->setAttribute('data-submit', 'form1')
));

$f = $this->html->form();
$len = count($this->models);
foreach($this->models as $index=>$model):
$f->newField($this->locale->_('code'), true,
    $this->html->textbox('codes_'.$index, 'codes['.$index.']', $model->getCode())
    ->addValidateRule('required', true));
$f->newField($this->locale->_('text'), true,
    $this->html->textbox('labels_'.$index, 'labels['.$index.']', $model->getLabel())
    ->addValidateRule('required', true));
endforeach;
$this->assign('form', $f);

$this->beginScript('newDictionaryItem');
?>
    <script language="javascript">
        var curIndex = <?php echo $len; ?>;
        var fieldHtml = '<div class="form-group">' +
            '<label class="control-label" for="codes_{index}"><?php echo $this->locale->_('code'); ?></label>' +
            '<input type="text" value="" data-validate-required="true" id="codes_{index}" name="codes[{index}]" class="form-control">' +
            '</div>' +
            '<div class="form-group">' +
            '<label class="control-label" for="labels_{index}"><?php echo $this->locale->_('text'); ?></label>' +
            '<input type="text" value="" data-validate-required="true" id="labels_{index}" name="labels[{index}]" class="form-control">' +
            '</div>'+
            '<div class="form-group">' +
            '<button type="button" class="btn btn-default" id="delete_{index}" onclick="javascript:deleteField({index});"><?php echo $this->locale->_('delete'); ?></button>' +
            '</div>';

        $('#add').click(function () {
            $('<hr/>').appendTo('#form1');
            var h = $(fieldHtml.substitute({
                'index':curIndex
            })).appendTo('#form1');

            $('#form1').data('validator').reload();
            curIndex++;
        });

        function deleteField(index){
            $('#codes_' + index).parent().prev().remove();
            $('#codes_' + index).parent().remove();
            $('#labels_' + index).parent().remove();
            $('#delete_' + index).parent().remove();
            $('#form1').data('validator').reload();
        }
    </script>
<?php
$this->endScript();

echo $this->includeTemplate('layout\form');