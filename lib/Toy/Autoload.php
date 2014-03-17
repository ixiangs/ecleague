<?php
namespace Toy;

class Autoload
{

    public function autoload($className)
    {
        $subPath = str_replace('\\', ' ', $className);
        $subPath = str_replace('_', ' ', $className);
        $subPath = str_replace(' ', DIRECTORY_SEPARATOR, ucwords(trim($subPath))) . '.php';

        $fn = explode('\\', $className)[0];
        if (!is_null(self::$ignoreNamespaces)) {
            foreach (self::$ignoreNamespaces as $in) {
                if ($fn == $in) {
                    return include_once $subPath;
                }
            }
        }

        if (!is_null(self::$codeNamespaces)) {
            foreach (self::$codeNamespaces as $cn) {
                if($cn == $fn){
                    $fp = self::$codePath.DIRECTORY_SEPARATOR.$subPath;
                    $b = include_once $fp;
                    return $b;
                }
            }

            foreach (self::$codeNamespaces as $cn) {
                $fp = self::$codePath.$cn.DIRECTORY_SEPARATOR.$subPath;
                if(file_exists($fp)){
                    $b = include_once $fp;
                    class_alias($cn.'\\'.$className, $className, false);
                    return $b;
                }
            }
        }

        return include_once $subPath;
    }

    static public $ignoreNamespaces = null;
    static public $codeNamespaces = null;
    static public $codePath = null;

    static public function register()
    {
        spl_autoload_register(array(self::singleton(), 'autoload'));
    }

    static private $_instance = NULL;

    static private function singleton()
    {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}
