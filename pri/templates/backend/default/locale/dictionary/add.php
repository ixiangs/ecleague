<?php
$this->assign('breadcrumb', array(
    array('text' => $this->locale->_('locale_manage')),
    array('text' => $this->locale->_('locale_language_list'), 'url' => $this->router->buildUrl('list')),
    array('text' => $this->language->getName(), 'active' => true)
));

$this->assign('buttons', array(
    array('text' => $this->locale->_('back'), 'url' => $this->router->buildUrl('list')),
    $this->html->button('button', $this->locale->_('new'), 'btn btn-success')->setId('new'),
    $this->html->button('button', $this->locale->_('save'), 'btn btn-primary')->addAttribute('data-submit', 'form1')
));

$f = $this->html->form();
$len = count($this->models);
foreach($this->models as $index=>$model):
$f->addInputField('text', $this->locale->_('code'), 'code_'.$index, 'code['.$index.']', $model->getCode())
    ->setId('code_field_'.$index)
    ->addAttribute('data-max-index', $len - 1)
    ->addValidateRule('required', true);
$f->addInputField('text', $this->locale->_('text'), 'name_'.$index, 'name['.$index.']', $model->getName())
    ->setId('text_field_'.$index)
    ->addAttribute('data-max-index', $len - 1)
    ->addValidateRule('required', true);
endforeach;
$this->assign('form', $f);

$this->beginBlock('footerjs');
?>
    <script language="javascript">
        $('#new').click(function () {
            $('<hr/>').appendTo('#form1');
            var el = $('#code_field_0');
            var mi = parseInt(el.attr('data-max-index'));
            el.attr('data-max-index', mi + 1);
            var cel = el.clone().removeClass('has-error');
            cel.attr('id', 'code_field_' + (mi + 1));
            cel.find('input').attr({'id': 'code_' + (mi + 1), 'name': 'code[' + (mi + 1) + ']'});
            cel.find('small').remove();
            cel.appendTo('#form1');

            el = $('#text_field_0');
            mi = parseInt(el.attr('data-max-index'));
            el.attr('data-max-index', mi + 1);
            cel = el.clone().removeClass('has-error');
            cel.attr('id', 'text_field_' + (mi + 1));
            cel.find('input').attr({'id': 'text_' + (mi + 1), 'name': 'text[' + (mi + 1) + ']'});
            cel.find('small').remove();
            cel.appendTo('#form1');

            $('#form1').data('validator').reload();
        });
    </script>
<?php
$this->endBlock();

echo $this->includeTemplate('layout\form');