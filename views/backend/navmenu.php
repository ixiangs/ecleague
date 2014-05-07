<div class="subnavbar">
    <div class="subnavbar-inner">
        <div class="container">
            <div class="collapse subnav-collapse">
                <ul class="mainnav">
                    <?php
                    $langId = $this->locale->getLanguageId();
                    $menus = \Ixiangs\System\MenuModel::find()
                        ->asc('parent_id', 'position')
                        ->load();
                    for ($_mi = 0; $_mi < count($menus); $_mi++):
                        if ($menus[$_mi]->parent_id == 0):
                            echo '<li class="dropdown">';
                            if($menus[$_mi]->url):
                                echo '<a href="'.$this->router->buildUrl($menus[$_mi]->url).'">';
                            else:
                                echo '<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">';
                            endif;
                            echo '<span>'.$menus[$_mi]->name[$langId].'</span>';
                            echo '</a><ul class="dropdown-menu">';
                            echo _renderChildrenMenu($menus, $menus[$_mi]->id, $langId, $this->router);
                            echo '</ul></li>';
                        endif;
                    endfor;

                    function _renderChildrenMenu($menus, $parentId, $langId, $router)
                    {
                        $res = '';
                        $found = false;
                        for ($i = 0; $i < count($menus); $i++) {
                            if ($menus[$i]->parent_id == $parentId) {
                                $cres = _renderChildrenMenu($menus, $menus[$i]->id, $langId, $router);
                                if(empty($cres)){
                                    $res .= '<li>';
                                    $res .= '<a href="'.$router->buildUrl($menus[$i]->url).'">';
                                    $res .= $menus[$i]->name[$langId];
                                    $res .= '</a></li>';
                                }else{
                                    $res .= '<li class="dropdown-submenu">';
                                    $res .= '<a href="#">';
                                    $res .= $menus[$i]->name[$langId];
                                    $res .= '</a><ul class="dropdown-menu">';
                                    $res .= $cres;
                                    $res .= '</ul></li>';
                                }

                            }
                        }
                        if ($found) {
                            echo '</li>';
                        }
                        return $res;
                    }

                    //_recursionMenu($this->menus, 0, $clang['id'], $this->locale, $this->router);
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>