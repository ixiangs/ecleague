<?php
namespace Toy\Web;

class Configuration {

	static public $domains = array();
    static public $seoUrl = true;
    static public $seoParameter = true;
//	static public $templateExtensions = array('.php');
//	static public $templateDirectories = null;
//	static public $templateFunctions = array();
//	static public $templateTheme = 'default';
    static public $indexUrl = '/';
	static public $configurationPath = '';
    static public $logger = null;
	static public $trace = false;

	static public function addDomain($name, $namespace, $startUrl, $default = false) {
		$d = new Domain($name, $namespace, $startUrl, $default);
		self::$domains[$name] = $d;
	}
}