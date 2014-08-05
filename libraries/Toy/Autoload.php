<?php
namespace Toy;

class Autoload
{

    static public $codeNamespaces = null;
    static public $ignoreNamespaces = null;
    static public $codePath = null;

    public function autoload($className)
    {
        $names = explode('\\', $className);

        if (is_array(self::$ignoreNamespaces) && in_array($names[0], self::$ignoreNamespaces)) {
            return self::loadClass($className);
        }

        if (is_array(self::$codeNamespaces) && in_array($names[0], self::$codeNamespaces)) {
            return self::loadClass($className);
        }

        if (is_array(self::$codeNamespaces)) {
            $subPath = str_replace(array('\\', '_'), ' ', $className);
            $subPath = str_replace(' ', DIRECTORY_SEPARATOR, ucwords(trim($subPath))) . '.php';
            foreach (self::$codeNamespaces as $cn) {
                $path = self::$codePath . $cn . DIRECTORY_SEPARATOR . $subPath;
                if (file_exists($path)) {
                    return include_once $path;
                }
            }
        }
        return self::loadClass($className);
    }

    static private function loadClass($className)
    {
        $subPath = str_replace(array('\\', '_'), ' ', $className);
        $subPath = str_replace(' ', DIRECTORY_SEPARATOR, ucwords(trim($subPath))) . '.php';
        return include_once $subPath;
    }

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
