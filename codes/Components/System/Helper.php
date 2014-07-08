<?php
namespace Components\System;


use Components\System\Models\ComponentModel;

class Helper {

    static public function getComponentId($code){
        return ComponentModel::find()
                ->select('id')
                ->eq('code', $code)
                ->fetchFirstValue();
    }
} 