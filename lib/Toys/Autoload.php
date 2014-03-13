<?php
namespace Toys;

class Autoload {

    public function autoload($className){
        $subPath = str_replace('\\', ' ', $className);
        $subPath = str_replace('_', ' ', $className);
        $subPath = str_replace(' ', DIRECTORY_SEPARATOR, ucwords(trim($subPath))).'.php';
        
        return include_once $subPath;
    }

    static public function register(){
        spl_autoload_register(array(self::singleton(), 'autoload'));
    }

    static private $_instance = NULL;
    static private function singleton(){
        if(!self::$_instance){
            self::$_instance = new self();
        }
        return self::$_instance;
    }		
}
