<?php
namespace Toys;

use Toys\Log\Logger;
use Toys\Data\Db;

class Joy {
	
	private static $_helpers = array();
	
	public static function addHelper($name, $class){
		self::$_helpers[$name] = $class;
	}

	public static function logger() {
		return Logger::singleton();
	}

	public static function db($name = null) {
		return Db::current($name);
	}
	
	public static function __callStatic($name, $arguments) {
		if(array_key_exists($name, self::$_helpers)){
			return self::$_helpers[$name];
		}
		return null;
	}
}
