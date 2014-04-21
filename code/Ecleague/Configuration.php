<?php
namespace Ecleague;

class Configuration {

	static public $domains = array();

	static public function addDomain($name, $namespace, $startUrl, $default = false) {
		$d = new Domain($name, $namespace, $startUrl, $default);
		self::$domains[$name] = $d;
	}
}