<?php
$this->assign('breadcrumb', array(
    $this->html->anchor($this->locale->_('admin_menu_manage')),
    $this->html->anchor($this->locale->_('sort'))
));

$this->assign('navigationBar', array(
    $this->html->anchor($this->locale->_('back'), $this->router->buildUrl('list'))
));

$clang = $this->locale->getCurrentLanguage();
$this->beginBlock('datalist');
?>
    <div class="row">
        <form id="table_form" method="post">
            <div class="panel panel-default">
                <div class="panel-heading text-right">
                    <?php echo $this->html->button('button', $this->locale->_('save'), 'btn btn-primary')
                                ->setEvent('click', 'saveSort()')->render(); ?>
                </div>
                <div class="panel-body">
                    <div class="dd" id="nestable">
                        <?php
                        function _recursionMenu($menus, $parentId, $langId, $locale, $router)
                        {
                            $found = false;
                            for ($i = 0; $i < count($menus); $i++) {
                                if ($menus[$i]->parent_id == $parentId) {
                                    if (!$found) {
                                        $found = true;
                                        echo '<ol class="dd-list">';
                                    }
                                    echo '<li class="dd-item" data-id="' . $menus[$i]->id . '">';
                                    echo '<div class="dd-handle">';
                                    echo $menus[$i]->names[$langId];
                                    echo '</div>';
                                    _recursionMenu($menus, $menus[$i]->id, $langId, $locale, $router);
                                    echo '</li>';
                                }
                            }
                            if ($found) {
                                echo '</ol>';
                            }
                            return $found;
                        }

                        _recursionMenu($this->menus, 0, $clang['id'], $this->locale, $this->router);
                        ?>
                    </div>
                </div>
            </div>
            <input type="hidden" name="data" id="data" value=""/>
        </form>
    </div>
<?php
$this->endBlock();
$this->nextBlock('headcss');
echo '<link href="/pub/assets/css/nestable.css" rel="stylesheet">';
$this->nextBlock('headjs');
echo '<script src="/pub/assets/js/jquery.nestable.js"></script>';
$this->nextBlock('footerjs');
?>
    <script language="javascript">
        $(document).ready(function () {
            $('#nestable').nestable({group: 1});
        });

        function saveSort(){
            $('#data').val(JSON.encode($('#nestable').nestable('serialize')));
            $('#table_form').submit();
        }
    </script>
<?php
$this->endBlock();
echo $this->includeTemplate('layout\list');