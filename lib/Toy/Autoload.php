<?php
namespace Toy;

use Toy\Platform\PathUtil;

class Autoload
{

    public function autoload($className)
    {
        if (array_key_exists($className, self::$_classes)) {
            $b = include_once self::$_classes[$className].'.php';
//            class_alias(self::$_classes[$className], $className);
            return $b;
        } else {
            $subPath = str_replace(array('\\', '_'), ' ', $className);
            $subPath = str_replace(' ', DIRECTORY_SEPARATOR, ucwords(trim($subPath))) . '.php';
            return include_once $subPath;
        }

    }

    static public $codePath = null;
//    static public $topNamespaces = array();
    static private $_classes = null;

    static public function register()
    {
        PathUtil::scanCurrent(self::$codePath, function ($first, $finfo) {
            PathUtil::scanRecursive($first, function ($second, $sinfo) use ($first, $finfo) {
                if ($sinfo['extension'] == 'php') {
                    $path = str_replace(self::$codePath, '', $second);
                    $an = str_replace(array($finfo['filename'] . '\\', '.php'), '', $path);
                    $sn = str_replace('.php', '', $path);
                    self::$_classes[$an] = $sn;
                }
            });
        });

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
