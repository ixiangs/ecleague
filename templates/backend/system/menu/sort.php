<?php
$this->assign('breadcrumb', array(
    $this->html->anchor($this->localize->_('admin_menu_manage')),
    $this->html->anchor($this->localize->_('sort'))
));

$this->assign('navigationBar', array(
    $this->html->anchor($this->localize->_('back'), $this->router->buildUrl('list'))
));

$this->assign('toolbar', array(
    $this->html->button('button', $this->localize->_('save'), 'btn btn-primary')
        ->setEvent('click', 'saveSort()')
));

$this->beginBlock('form');
?>
<div class="row">
    <form id="table_form" method="post">
        <ul id="menutree" class="ztree"></ul>
        <input type="hidden" name="data" id="data" value=""/>
    </form>
</div>
<?php
$this->nextBlock('headcss');
echo '<link href="/static/css/ztree/zTreeStyle.css" rel="stylesheet">';
$this->nextBlock('headjs');
echo '<script src="/static/js/jquery.ztree.js"></script>';
$this->nextBlock('footerjs');
?>
    <script language="javascript">
        var setting = {
            edit: {
                enable: true,
                showRemoveBtn: false,
                showRenameBtn: false
            },
            data: {
                simpleData: {
                    enable: true
                }
            }
        };

        var zNodes =[
            <?php
            $nodes = array();
            foreach($this->menus as $menu):
            $nodes[] = sprintf('{ id:%s, pId:%s, name:"%s", open:true}',
                                $menu->getId(), $menu->getParentId(0), $menu->name);
            endforeach;
            echo implode(',', $nodes);
            ?>
        ];

        $(document).ready(function(){
            $.fn.zTree.init($("#menutree"), setting, zNodes);
        });

        function saveSort(){
            var treeObj = $.fn.zTree.getZTreeObj("menutree");
            var nodes = treeObj.transformToArray(treeObj.getNodes());
            $('#table_form input[type="hidden"]').remove();
            for(var i = 0; i < nodes.length; i++){
                $('#table_form').append('<input type="hidden" name="data[' + i + '][id]" value="' + nodes[i].id + '"/>');
                $('#table_form').append('<input type="hidden" name="data[' + i + '][parent_id]" value="' + nodes[i].pId + '"/>');
                $('#table_form').append('<input type="hidden" name="data[' + i + '][position]" value="' + treeObj.getNodeIndex(nodes[i]) + '"/>');
            }
            $('#table_form').submit();
        }
    </script>
<?php
$this->endBlock();
echo $this->includeTemplate('layout\form');