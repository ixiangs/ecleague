<?php
namespace Void\Core;

class Helper {

    static public function getComponentId($code){
        return ComponentModel::find()
                ->select('id')
                ->eq('code', $code)
                ->fetchFirstValue();
    }
} 