<?php
namespace Localization;
use Toy\Util\PathUtil;
use Toy\Util\FileUtil;
use Toy\Util\StringUtil;

class Dictionary implements \ArrayAccess {

	private $_texts = array();

	private function __construct() {
	}
	
	public function __get($name){
		return $this->_texts[$name];
	}

	public function get($name) {
		return $this -> _texts[$name];
	}

	public function format() {
		$args = func_get_args();
		return call_user_func_array('sprintf', $args);
	}

	public function offsetExists($offset) {
		return array_key_exists($offset, $this -> _texts);
	}

	public function offsetGet($offset) {
		return $this -> _texts[$offset];
	}

	public function offsetSet($offset, $value) {
		$this -> _texts[$offset] = $value;
	}

	public function offsetUnset($offset) {
		unset($this -> _texts[$offset]);
	}
	
	public function initialize($lang) {
		$path = PathUtil::combines(Configuration::$languageDirectory, $lang);
		$files = array();
		PathUtil::scanCurrent($path, function($file, $info) use(&$files){
			if ($info['extension'] == 'csv') {
				$files[] = $file;
			}
		});

		foreach ($files as $file) {
			$lines = FileUtil::readCsv($file);
			for ($i = 0; $i < count($lines); $i++) {
				$this->_texts[$lines[$i][0]] = $lines[$i][1];
			}
		}
	}
	
	private static $_instance = NULL;
	public static function singleton(){
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;		
	}
}
