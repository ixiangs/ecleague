<?php
namespace Void\System;


use Void\System\ComponentModel;

class Helper {

    static public function getComponentId($code){
        return ComponentModel::find()
                ->select('id')
                ->eq('code', $code)
                ->fetchFirstValue();
    }
} 