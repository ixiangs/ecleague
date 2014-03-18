<?php
namespace Toy\Util;

class RandomUtil {
    
    private static $_characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    static public function randomMd5($str) {
        return md5($str);
    }  
    
    static public function randomNumeric($min = 0, $max = 99999999){
		return mt_rand($min, $max);
    }
    
    static public function randomCharacters($size = 6, $characters = NULL){
        if(is_null($characters)){
            $characters = self::$_characters;
        }
        $characters .= $characters;
    	$result = '';
    	$len = strlen($characters) - 1;
    	for($i = 0; $i < $size; $i++){
    		$index = mt_rand(0, $len);
    		$result .= $characters[$index];
    	}
    	return $result;
    }
}