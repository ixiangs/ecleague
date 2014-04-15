<?php
$this->assign('breadcrumb', array(
    $this->html->anchor($this->locale->_('dass_manage')),
    $this->html->anchor($this->attribute->names[$this->locale->getCurrentLanguageId()]),
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
$f->addHiddenField('attribute_id', 'attribute_id', $this->attribute->getId());
$this->assign('form', $f);

$this->beginBlock('footerjs');
?>
    <script language="javascript">
        var curIndex = 0;
        var optionHtml = '<div class="panel panel-default"><div class="panel-body">' +
            '<div class="form-group">' +
            '<label class="col-sm-1 control-label" for="options_{index}_value"><?php echo $this->locale->_('dass_option_value'); ?></label>' +
            '<div class="col-sm-9"><input type="text" value="{ovalue}" data-validate-required="true" id="options_{index}_value" name="options[{index}][value]" class="form-control option-value">' +
            '</div></div>';
        <?php foreach($this->locale->getLanguages() as $lang): ?>
        optionHtml += '<div class="form-group">' +
            '<label class="col-sm-1 control-label" for="options_{index}_<?php $lang['id']?>"><?php echo $lang['name']; ?></label>' +
            '<div class="col-sm-9"><input type="text" value="{olabel<?php echo $lang['id']; ?>}" data-validate-required="true" id="options_{index}_label_<?php echo $lang['id']?>" name="options[{index}][labels][<?php echo $lang['id']?>]" class="form-control" data-validate-required="true">' +
            '</div></div>';
        <?php endforeach; ?>
        optionHtml += '</div><div class="panel-footer text-right"><button type="button" class="btn btn-danger" data-option-id="{id}" onclick="javascript:deleteOption(this);"><?php echo $this->locale->_('delete'); ?></button></div>';
        optionHtml += '<input type="hidden" name="options[{index}][id]" value="{id}"/></div>';

        $('#new').click(function () {
            newOption({});
            $('#form1').data('validator').reload();
        });

        function newOption(data) {
            data['index'] = curIndex;
            $(optionHtml.substitute(data)).appendTo('#form1');
            curIndex++;
        }

        function deleteOption(el) {
            if ($(el).attr('data-option-id')) {
                $('<input type="hidden" name="delete_ids[]" value="' + $(el).attr('data-option-id') + '"/>').appendTo('#form1');
            }
            $(el).parent().parent().remove();
        }


        $(function () {
            <?php
            if(count($this->options) > 0):
                foreach($this->options as $index=>$option):
                echo 'var data'.$index.'={"id":"'.$option->getId().'",ovalue:"'.$option->getValue().'"};';
                foreach($option->getLabels() as $lid=>$label):
                    echo 'data'.$index.'["olabel'. $lid.'"] = "'.$label.'";', "\n";
                endforeach;
                echo 'newOption(data'.$index.');', "\n";
                endforeach;
            else:
                echo 'newOption({});';
            endif;
            ?>

            var tvv = new Toy.Validation.Validator('#form1', {
                autoSubmit: false
            });

            $('#form1').submit(function(event){
               if(tvv.validate()){
                   var ovalues = [];
                   var roptions = [];
                   $('.option-value').each(function(){
                       if(ovalues.contains($(this).val())){
                           roptions.push($(this));
                           $(this).parent().parent().removeClass('has-success').addClass('has-error');
                           return;
                       }
                       ovalues.push($(this).val());
                   });
                   if(roptions.length > 0){
                       alert('<?php echo $this->locale->_('dass_err_option_repeated'); ?>');
                       event.preventDefault();
                   }
               }else{
                   event.preventDefault();
               }
            });
        });

    </script>
<?php
$this->endBlock();

echo $this->includeTemplate('layout\form');