<?php
namespace Toys\Util;

class RandomUtil {
    
    private static $_characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    public static function randomMd5($str) {
        return md5($str);
    }  
    
    public static function randomNumeric($min = 0, $max = 99999999){
		return mt_rand($min, $max);
    }
    
    public static function randomCharacters($size = 6, $characters = NULL){
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