<?php
namespace Toy\Web;

class Configuration {

    static public $initializerClass = null;
    static public $handlerClass = '\Toy\Web\Framework\Handler';
    static public $rendererClass = '\Toy\Web\Framework\Renderer';
    static public $routerClass = '\Toy\Web\Framework\Router';
    static public $sessionClass = '\Toy\Http\Session';
    static public $requestClass = '\Toy\Http\Request';
    static public $responseClass = '\Toy\Http\Response';

//	static public $domains = array();
//    static public $seoUrl = true;
//    static public $seoParameter = true;
//	static public $templateExtensions = array('.php');
//	static public $templateDirectories = null;
//	static public $templateFunctions = array();
//	static public $templateTheme = 'default';
//    static public $indexUrl = '/';
//	static public $configurationPath = '';
    static public $logger = null;
	static public $trace = false;

//	static public function addDomain($name, $namespace, $startUrl, $default = false) {
//		$d = new Domain($name, $namespace, $startUrl, $default);
//		self::$domains[$name] = $d;
//	}
}