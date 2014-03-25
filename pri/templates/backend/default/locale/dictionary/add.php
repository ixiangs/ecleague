<?php
$this->assign('breadcrumb', array(
    array('text' => $this->locale->_('locale_manage')),
    array('text' => $this->locale->_('locale_language_list'), 'url' => $this->router->buildUrl('language/list')),
    array('text' => $this->language->getName(), 'url' => $this->router->buildUrl('list', array('languageid'=>$this->language->getId())))
));

$this->assign('buttons', array(
    array('text' => $this->locale->_('back'), 'url' => $this->router->buildUrl('list', array('languageid'=>$this->language->getId()))),
    $this->html->button('button', $this->locale->_('new'), 'btn btn-success')->setAttribute('id', 'new'),
    $this->html->button('button', $this->locale->_('save'), 'btn btn-primary')->setAttribute('data-submit', 'form1')
));

$f = $this->html->form();
$len = count($this->models);
foreach($this->models as $index=>$model):
$f->addInputField('text', $this->locale->_('code'), 'codes_'.$index, 'codes['.$index.']', $model->getCode())
    ->addValidateRule('required', true);
$f->addInputField('text', $this->locale->_('text'), 'labels_'.$index, 'labels['.$index.']', $model->getLabel())
    ->addValidateRule('required', true);
endforeach;
$this->assign('form', $f);

$this->beginBlock('footerjs');
?>
    <script language="javascript">
        var curIndex = <?php echo $len; ?>;
        var fieldHtml = '<div class="form-group">' +
            '<label class="col-lg-2 control-label" for="codes_{index}"><?php echo $this->locale->_('code'); ?></label>' +
            '<div class="col-lg-10"><input type="text" value="" data-validate-required="true" id="codes_{index}" name="codes[{index}]" class="form-control">' +
            '</div></div>' +
            '<div class="form-group">' +
            '<label class="col-lg-2 control-label" for="labels_{index}"><?php echo $this->locale->_('text'); ?></label>' +
            '<div class="col-lg-10"><input type="text" value="" data-validate-required="true" id="labels_{index}" name="labels[{index}]" class="form-control">' +
            '</div></div>'+
            '<div class="form-group"><div class="col-lg-2"></div><div class="col-lg-10">' +
            '<button type="button" id="delete_{index}" onclick="javascript:deleteField({index});"><?php echo $this->locale->_('delete'); ?></button>' +
            '</div></div>';

        $('#new').click(function () {
            $('<hr/>').appendTo('#form1');
            var h = $(fieldHtml.substitute({
                'index':curIndex
            })).appendTo('#form1');

            $('#form1').data('validator').reload();
            curIndex++;
        });

        function deleteField(index){
            $('#codes_' + index).parent().parent().prev().remove();
            $('#codes_' + index).parent().parent().remove();
            $('#labels_' + index).parent().parent().remove();
            $('#delete_' + index).parent().parent().remove();
            $('#form1').data('validator').reload();
        }
    </script>
<?php
$this->endBlock();

echo $this->includeTemplate('layout\form');