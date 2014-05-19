<?php
$this->assign('breadcrumb', array(
    $this->html->anchor($this->locale->_('attrs_manage')),
    $this->html->anchor($this->entity->getName()),
    $this->html->anchor($this->locale->_('grouping'))
));

$this->assign('navigationBar', array(
    $this->html->anchor($this->locale->_('back'), $this->router->buildUrl('list'))
));

$this->assign('toolbar', array(
    $this->html->button('button', $this->locale->_('attrs_new_group'), 'btn btn-success')
        ->setAttribute(array('data-toggle'=>"modal",'data-target'=>"#group_dialog")),
    $this->html->button('button', $this->locale->_('save'), 'btn btn-primary')
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
        $f->newField($this->locale->_('name'), true,
            $this->html->textbox('name', 'data[name]')
                ->addValidateRule('required', true));
        $f->addHidden('group_id', 'data[id]', '');
        $f->addHidden('entity_id', 'data[entity_id]', $this->entity->getId());
        $f->addHidden('component_id', 'data[component_id]', $this->entity->getComponentId());
        echo $f->render();
        ?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->locale->_('close') ?></button>
        <button type="button" class="btn btn-primary" data-submit="group_form" data-ajax="true" data-ajax-handler="handleGroupSaved"><?php echo $this->locale->_('save') ?></button>
    </div>
    </div>
    </div>
    </div>
<?php
$this->nextBlock('headcss');
echo $this->renderCss(CSS_URL.'ztree/zTreeStyle.css');
$this->nextBlock('headjs');
echo $this->renderJavascript(JS_URL.'jquery.ztree.js');
$this->nextBlock('footerjs');
?>
    <script language="javascript">
        var groupTree = {
            edit: { enable: true, showRemoveBtn: false, showRenameBtn: false },
            data: { simpleData: { enable: true }}
        };
        var groupNodes =[
            <?php
            $nodes = array();
            foreach($this->groups as $group):
                $nodes[] = sprintf('{ id:%s, name:"%s", isParent:true, open:true}',
                                    $group->getId(), $group->name);
                $groupAttributes = $group->getAttributeIds();
                foreach($this->attributes as $attribute):
                    if(in_array($attribute->getId(), $groupAttributes)):
                        $nodes[] = sprintf('{ id:%s, pId:%s, name:"%s", open:true}',
                                        $attribute->getId(), $group->getId(), $attribute->name);
                endif;
                endforeach;
            endforeach;
            echo implode(',', $nodes);
            ?>
        ];

        var attributeTree = {
            edit: { enable: true, showRemoveBtn: false, showRenameBtn: false },
            data: { simpleData: { enable: true }}
        };
        var attributeNodes =[
            <?php
            $nodes = array();
            $entityAttributes = $this->entity->getAttributeIds();
            foreach($this->attributes as $attribute):
                if(!in_array($attribute->getId(), $entityAttributes)):
                    $nodes[] = sprintf('{ id:%s, name:"%s"}',
                                    $attribute->getId(), $attribute->name);
            endif;
            endforeach;
            echo implode(',', $nodes);
            ?>
        ];

        $(document).ready(function(){
            $.fn.zTree.init($("#group_tree"), groupTree, groupNodes);
            $.fn.zTree.init($("#attribute_tree"), attributeTree, attributeNodes);
        });

        function handleGroupSaved(responseText, statusText, xhr, form){
            var zTree = $.fn.zTree.getZTreeObj('group_tree');
            nodes = zTree.getSelectedNodes(),
            treeNode = nodes[0];
            zTree.addNodes(treeNode, {id:responseText.data.id, pId:0, isParent:true, name:responseText.data.name});
            $('#group_dialog').modal('hide');
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
$this->endBlock();
echo $this->includeTemplate('layout\form');