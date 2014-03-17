<?php
namespace Toy\Web;

class Configuration {

	public static $domains = array();
    public static $seoUrl = true;
    public static $seoParameter = true;
	public static $templateExtensions = array('.php');
	public static $templateDirectories = null;
	public static $templateFunctions = array();
	public static $templateTheme = 'default';
	public static $codeDirectory = '';
//    public static $codeNamespaces = array('Core');
    public static $logger = null;
	public static $trace = false;

	public static function addDomain($name, $namespace, $startUrl, $default = false) {
		$d = new Domain($name, $namespace, $startUrl, $default);
		self::$domains[$name] = $d;
	}
}