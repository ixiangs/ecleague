<?php
namespace Toy\Web;

class Configuration
{

    static public $initializerClass = '\Toy\Web\Initializer';
    static public $handlerClass = '\Toy\Web\Handler';
    static public $rendererClass = '\Toy\Web\Renderer';
    static public $localizeClass = '\Toy\Web\Localize';
    static public $routerClass = '\Toy\Web\Router';
    static public $filterClass = '\Toy\Web\Filter';
    static public $sessionClass = '\Toy\Http\Session';
    static public $requestClass = '\Toy\Http\Request';
    static public $responseClass = '\Toy\Http\Response';
    static public $languagePath = null;
    static public $templateExtensions = array('.php');
    static public $templateRoot = '';
    static public $templateTheme = '';
    static public $componentDirectory = null;
    static public $logger = null;
    static public $trace = false;
    static public $defaultDomain = null;
    static public $domains = array();

    static public function addDomain($name, $namespace,
                                     $startUrl, $indexUrl,
                                     $loginUrl = '', $authorizationRequired = false,
                                     $default = false)
    {
        $d = new Domain($name, $namespace, $startUrl, $indexUrl, $loginUrl, $authorizationRequired, $default);
        self::$domains[$name] = $d;
        if ($default) {
            self::$defaultDomain = $name;
        }
    }
}