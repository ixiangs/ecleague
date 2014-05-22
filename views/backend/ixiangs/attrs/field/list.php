<?php
$this->assign('breadcrumb', array(
    $this->html->anchor($this->locale->_('attrs_manage')),
    $this->html->anchor($this->entity->getName()),
    $this->html->anchor($this->locale->_('attrs_field_list'))
));

$this->assign('navigationBar', array(
    $this->html->anchor($this->locale->_('back'), $this->router->buildUrl('list'))
        ->setAttribute('class', 'btn btn-default'),
    $this->html->button('button', $this->locale->_('attrs_select_attribute'), 'btn btn-success')
        ->setAttribute(array('data-toggle' => "modal", 'data-target' => "#attribute_dialog")),
    $this->html->button('button', $this->locale->_('save'), 'btn btn-primary')
));

$this->beginBlock('datalist');
$inputTypes = array(
    \Ixiangs\Attrs\Constant::DATA_TYPE_STRING=>$this->locale->_('attrs_data_type_string'),
    \Ixiangs\Attrs\Constant::DATA_TYPE_INTEGER=>$this->locale->_('attrs_data_type_integer'),
    \Ixiangs\Attrs\Constant::DATA_TYPE_NUMBER=>$this->locale->_('attrs_data_type_number'),
    \Ixiangs\Attrs\Constant::DATA_TYPE_BOOLEAN=>$this->locale->_('attrs_data_type_boolean'),
    \Ixiangs\Attrs\Constant::DATA_TYPE_DATE=>$this->locale->_('attrs_data_type_date'),
    \Ixiangs\Attrs\Constant::DATA_TYPE_EMAIL=>$this->locale->_('attrs_data_type_email'),
    \Ixiangs\Attrs\Constant::DATA_TYPE_ARRAY=>$this->locale->_('attrs_data_type_array')
);
$dataTypes = array(
    \Ixiangs\Attrs\Constant::INPUT_TYPE_TEXTBOX=>$this->locale->_('attrs_input_type_textbox'),
    \Ixiangs\Attrs\Constant::INPUT_TYPE_TEXTAREA=>$this->locale->_('attrs_input_type_textarea'),
    \Ixiangs\Attrs\Constant::INPUT_TYPE_EDITOR=>$this->locale->_('attrs_input_type_editor'),
    \Ixiangs\Attrs\Constant::INPUT_TYPE_SELECT=>$this->locale->_('attrs_input_type_select'),
    \Ixiangs\Attrs\Constant::INPUT_TYPE_OPTION_LIST=>$this->locale->_('attrs_input_type_option_list'),
    \Ixiangs\Attrs\Constant::INPUT_TYPE_DATE_PICKER=>$this->locale->_('attrs_input_type_datepicker')
);
?>
    <div class="row">
        <form id="table_form" method="post">
            <table id="table1" class="table table-striped table-bordered table-hover">
                <thead>
                <tr>
<!--                    <th class="index">#</th>-->
                    <th><?php echo $this->locale->_('name'); ?></th>
                    <th class="small"><?php echo $this->locale->_('attrs_data_type'); ?></th>
                    <th class="small"><?php echo $this->locale->_('attrs_input_type'); ?></th>
                    <th class="small"><?php echo $this->locale->_('attrs_required'); ?></th>
                    <th class="small"><?php echo $this->locale->_('attrs_indexable'); ?></th>
                    <th class="edit"></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($this->fields as $index => $field): ?>
                    <tr>
<!--                        <td class="index"><span>--><?php //echo $index + 1; ?><!--</span></td>-->
                        <td><span><?php echo $field->name; ?></span></td>
                        <td><span><?php echo $field->name; ?></span></td>
                        <td><span><?php echo $field->name; ?></span></td>
                        <td class="small text-center">
                            <input type="checkbox" name="data[<?php echo $field->id; ?>][required]"
                                   value="1"<?php echo $field->required ? ' checked="checked"' : "" ?>/>
                        </td>
                        <td class="small text-center">
                            <input type="checkbox" name="data[<?php echo $field->id; ?>][indexable]"
                                   value="1"<?php echo $field->indexable ? ' checked="checked"' : "" ?>/>
                        </td>
                        <td class="edit">
                            <a onclick="deleteConfirm('/admin/ixiangs_entity_field_delete?entityid=1')"
                               class="btn btn-link"><?php echo $this->locale->_('delete'); ?></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </form>
    </div>
    <div class="modal fade" id="attribute_dialog" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <?php foreach($this->attributes as $attribute): ?>
                            <div class="col-md-4">
                                <input type="checkbox" data-name="<?php echo $attribute->getName(); ?>" data-data-type="<?php echo $attribute->getDataType(); ?>" data-input-type="<?php echo $attribute->getInputType(); ?>" value="<?php echo $attribute->getId(); ?>"/><?php echo $attribute->getName(); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->locale->_('close'); ?></button>
                    <button type="button" class="btn btn-primary"><?php echo $this->locale->_('ok'); ?></button>
                </div>
            </div>
        </div>
    </div>
<?php
$this->endBlock();
$this->beginScript('fieldpage');
?>
<script language="javascript">
    var inputTypes = <?php echo json_encode($inputTypes); ?>;
    var dataTypes = <?php echo json_encode($dataTypes); ?>;
    var rowHtml = [];
    rowHtml.push('<tr>');
    rowHtml.push('<td><span>{name}</span></td>');
    rowHtml.push('<td class="small"><span>{dataType}</span></td>');
    rowHtml.push('<td class="small"><span>{inputType}</span></td>');
    rowHtml.push('<td class="small"><span>{inputType}</span></td>');
    rowHtml.push('<td class="small text-center"><input type="checkbox" name="newfiels[{index}][required]" value="1"/></td>');
    rowHtml.push('<td class="small text-center"><input type="checkbox" name="newfiels[{index}][indexable]" value="1"/></td>');
    rowHtml.push('<td class="edit"><a onclick=""  class="btn btn-link"><?php echo $this->locale->_('delete'); ?></a></td>');
    rowHtml.push('</tr>');

    function newFieldRow(){

    }
</script>
<?php
$this->endScript();
echo $this->includeTemplate('layout\list');