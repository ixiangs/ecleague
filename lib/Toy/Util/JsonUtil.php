<?php
namespace Toy\Util;

class JsonUtil {

    public static function encode(array $data, $apostrophe = false){
        $result = json_encode($data);
        if($apostrophe){
            $result = str_replace('"', "'", $result);
        }
        return $result;
    }

    public static function decode(){
        
    }
}