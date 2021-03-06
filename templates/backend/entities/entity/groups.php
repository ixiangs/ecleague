<?php
$this->assign('navigationBar', array(
    $this->html->anchor($this->localize->_('back'), $this->router->buildUrl('list'))
));

$this->assign('toolbar', array(
    $this->html->button('button', $this->localize->_('entities_group_new'), 'btn btn-success')
        ->setAttribute(array('data-toggle'=>"modal",'data-target'=>"#group_dialog")),
    $this->html->button('button', $this->localize->_('save'), 'btn btn-primary')
));
$this->beginBlock('form');
?>
    <form id="form1" method="post">
        <div class="row">
            <div class="col-md-6">
                <ul id="group_tree" class="ztree" style="border: 1px solid #617775;background: #f0f6e4;height: 360px;overflow-y: scroll;overflow-x: auto;"></ul>
            </div>
            <div class="col-md-6">
                <ul id="attribute_tree" class="ztree" style="border: 1px solid #617775;background: #f0f6e4;height: 360px;overflow-y: scroll;overflow-x: auto;"></ul>
            </div>
            <input type="hidden" name="data" id="data" value=""/>
        </div>
        <input type="hidden" name="entity_id" value="<?php echo $this->entity->getId(); ?>"/>
        <input type="hidden" name="component_id" value="<?php echo $this->entity->getComponentId(); ?>"/>
    </form>
    <div id="group_dialog" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
    <div class="modal-body">
        <?php
        $f = $this->html->form('group_form')
            ->setAttribute(array(
                'action'=>$this->router->buildUrl('save-group', '*')
            ));
        $f->newField($this->localize->_('name'), true,
            $this->html->textbox('group_name', 'group_name')
                ->addValidateRule('required', true));
        $f->addHidden('group_id', 'group_id', '');
        echo $f->render();
        ?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->localize->_('close') ?></button>
        <button type="button" class="btn btn-primary" id="save_group"><?php echo $this->localize->_('save') ?></button>
    </div>
    </div>
    </div>
    </div>
<?php
$this->endBlock();
$this->document->addReferenceCss('ztree', CSS_URL.'ztree/zTreeStyle.css');
$this->document->addReferenceScript('ztree',  JS_URL.'jquery.ztree.js');
$this->beginScript('attrsFieldGroup');
?>
    <script language="javascript">
        var groupTree = {
            edit: { enable: true,
                    showRemoveBtn: false,
                    showRenameBtn: false,
                    removeTitle:"<?php echo $this->localize->_('delete'); ?>" },
            data: { simpleData: { enable: true }},
            callback:{onRemove: onRemoveGroup}
        };
        var groupNodes =[
            <?php
            $nodes = array();
            foreach($this->groups as $group):
                $nodes[] = sprintf('{ id:%s, name:"%s", isParent:true, open:true}',
                                    $group->getId(), $group->name);
                $groupFields = $group->getFieldIds();
                foreach($this->fields as $field):
                    if(in_array($field->getId(), $groupFields)):
                        $nodes[] = sprintf('{ id:%s, pId:%s, name:"%s", open:true}',
                                        $field->getId(), $group->getId(), $field->getName());
                endif;
                endforeach;
            endforeach;
            echo implode(',', $nodes);
            ?>
        ];

        var attributeTree = {
            edit: { enable: true,
                    showRemoveBtn: false,
                    showRenameBtn: false },
            data: { simpleData: { enable: true }}
        };
        var attributeNodes =[
            <?php
            $nodes = array();
            foreach($this->fields as $field):
                    $nodes[] = sprintf('{ id:%s, name:"%s"}', $field->getId(), $field->getName());
            endforeach;
            echo implode(',', $nodes);
            ?>
        ];

        $(document).ready(function(){
            $.fn.zTree.init($("#group_tree"), groupTree, groupNodes);
            $.fn.zTree.init($("#attribute_tree"), attributeTree, attributeNodes);
            $('#save_group').click(onEditGroup);
        });

        function handleGroupSaved(responseText, statusText, xhr, form){
            var zTree = $.fn.zTree.getZTreeObj('group_tree');
            nodes = zTree.getSelectedNodes(),
            treeNode = nodes[0];
            zTree.addNodes(treeNode, {id:responseText.data.id, pId:0, isParent:true, name:responseText.data.name, open:true});
            $('#group_dialog').modal('hide');
        }

        function onRemoveGroup(event, treeId, treeNode){
            $('#form1').append('<input type="hidden" name="delete_group[]" value="' + treeNode.id + '" />')
        }

        function onEditGroup(){
            var $form = $('#group_form');
            var gname = $form.find('#group_name').val();
            var gid = $form.find('#group_id').val();
            var validator = $form.data('validator');
            if(validator.validate()){
                if(gid){
                    $('#form1').append('<input type="hidden" name="new_group_names[]" value="' + gname + '" />')
                }else{
                    $('#form1').append('<input type="hidden" name="edit_group_names[]" value="' + gname + '" />')
                }
            }

        }

        function saveSort(){
//            var treeObj = $.fn.zTree.getZTreeObj("groupTree");
//            var nodes = treeObj.transformToArray(treeObj.getNodes());
//            $('#table_form input[type="hidden"]').remove();
//            for(var i = 0; i < nodes.length; i++){
//                $('#table_form').append('<input type="hidden" name="data[' + i + '][id]" value="' + nodes[i].id + '"/>');
//                $('#table_form').append('<input type="hidden" name="data[' + i + '][parent_id]" value="' + nodes[i].pId + '"/>');
//                $('#table_form').append('<input type="hidden" name="data[' + i + '][position]" value="' + treeObj.getNodeIndex(nodes[i]) + '"/>');
//            }
//            $('#table_form').submit();
        }
    </script>
<?php
$this->endScript();
echo $this->includeTemplate('layout\form');