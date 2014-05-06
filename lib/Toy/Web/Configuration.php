<?php
namespace Toy\Web;

class Configuration
{

    static public $initializerClass = '\Toy\Web\Initializer';
    static public $handlerClass = '\Toy\Web\Handler';
    static public $rendererClass = '\Toy\Web\Renderer';
    static public $routerClass = '\Toy\Web\Router';
    static public $sessionClass = '\Toy\Http\Session';
    static public $requestClass = '\Toy\Http\Request';
    static public $responseClass = '\Toy\Http\Response';
    static public $componentDirectory = null;
    static public $controllerDirectory = null;
    static public $indexUrl = '/';
    static public $logger = null;
    static public $trace = false;
    static public $defaultDomain = null;
    static public $domains = array();

    static public function addDomain($name, $startUrl, $indexUrl, $loginUrl = '', $default = false)
    {
        $d = new Domain($name, $startUrl, $indexUrl, $loginUrl, $default);
        self::$domains[$name] = $d;
        if ($default) {
            self::$defaultDomain = $name;
        }
    }
}