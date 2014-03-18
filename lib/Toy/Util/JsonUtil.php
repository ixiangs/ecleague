<?php
namespace Toy\Util;

class JsonUtil {

    static public function encode(array $data, $apostrophe = false){
        $result = json_encode($data);
        if($apostrophe){
            $result = str_replace('"', "'", $result);
        }
        return $result;
    }

    static public function decode(){
        
    }
}