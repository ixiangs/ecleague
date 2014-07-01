<?php
namespace Toy;

final class Loader
{
    static public $path = null;
    static public $namespaces = array();
    static private $_singletons = array();
    static private $_classes = array();

    static public function load($className)
    {
        $names = explode("\\", $className);
        if (in_array($names[0], self::$namespaces)) {
            return $className;
        }

        if (array_key_exists($className, self::$_classes)) {
            return self::$_classes[$className];
        }

        $subpath = DIRECTORY_SEPARATOR . join(DIRECTORY_SEPARATOR, $names);
        foreach (self::$namespaces as $ns) {
            $fn = self::$path . $ns . $subpath . '.php';
            if (file_exists($fn)) {
                include_once $fn;
                $res = $ns . '\\' . implode('\\', $names);
                self::$_classes[$className] = $res;
                return $res;
            }
        }

        throw new \Exception(sprintf("Not found [%s] with [%s] in [%s]", $className, implode(',', self::$namespaces), self::$path));
    }

    static public function create($className, $args = array())
    {
        $cls = self::load($className);
        $cnt = count($args);
        switch ($cnt) {
            case 1:
                return new $cls($args[0]);
            case 2:
                return new $cls($args[0], $args[1]);
            case 3:
                return new $cls($args[0], $args[1], $args[2]);
            case 4:
                return new $cls($args[0], $args[1], $args[2], $args[3]);
            case 5:
                return new $cls($args[0], $args[1], $args[2], $args[3], $args[4]);
            default:
                return new $cls();
        }

    }

    static public function singleton($className, $args = array())
    {
        if (array_key_exists($className, self::$_singletons)) {
            return self::$_singletons[$className];
        }

        self::$_singletons[$className] = self::create($className, $args);
        return self::$_singletons[$className];
    }
}