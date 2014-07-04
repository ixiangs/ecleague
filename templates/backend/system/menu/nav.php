<div class="subnavbar">
<div class="subnavbar-inner">
<div class="container">
<div class="collapse subnav-collapse">
<ul class="mainnav">
<?php
    $allMenus = \Components\System\Models\MenuModel::find()
        ->eq('enabled', true)
        ->asc('parent_id', 'position')
        ->load();
    $menus = array();
    foreach($allMenus as $menu):
        $codes = $menu->getBehaviorCodes();
        if(count($codes) > 0):
            if($this->identity->hasAnyBehavior($codes)):
                $menus[] = $menu;
            endif;
        else:
            $menus[] = $menu;
        endif;
    endforeach;
    for ($_mi = 0; $_mi < count($menus); $_mi++):
        if ($menus[$_mi]->parent_id == 0):
            $sub = _renderChildrenMenu($menus, $menus[$_mi]->id, $this->router);
            if(!empty($sub)){
                echo '<li class="dropdown">';
                if($menus[$_mi]->url):
                    echo '<a href="'.$this->router->buildUrl($menus[$_mi]->url).'">';
                else:
                    echo '<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">';
                endif;
                echo '<span>'.$menus[$_mi]->name.'</span>';
                echo '</a><ul class="dropdown-menu">';
                echo $sub;
                echo '</ul></li>';
            }
        endif;
    endfor;

    function _renderChildrenMenu($menus, $parentId, $router)
    {
        $res = '';
        for ($i = 0; $i < count($menus); $i++) {
            if ($menus[$i]->parent_id == $parentId) {
                $cres = _renderChildrenMenu($menus, $menus[$i]->id, $router);
                if(empty($cres)){
                    $res .= '<li>';
                    $res .= '<a href="'.$router->buildUrl($menus[$i]->url).'">';
                    $res .= $menus[$i]->name;
                    $res .= '</a></li>';
                }else{
                    $res .= '<li class="dropdown-submenu">';
                    $res .= '<a href="#">';
                    $res .= $menus[$i]->name;
                    $res .= '</a><ul class="dropdown-menu">';
                    $res .= $cres;
                    $res .= '</ul></li>';
                }

            }
        }
        return $res;
    }
?>
</ul>
</div>
</div>
</div>
</div>