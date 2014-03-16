<?php
namespace Toy\Util;

class EncryptUtil{

    private static $_letters = 'abcdefghijklmnopqrstuvwxyz';

    public static function encryptPassword($str){
        $arr = array();
        $len = strlen($str);
        for($i = 0; $i < $len; $i++){
            $arr[] = md5($str[$i]);
        }
        return md5(join('|', $arr));
    }
}