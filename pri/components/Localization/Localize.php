<?php
namespace Localization;
use Toys\Util\PathUtil;
use Toys\Util\FileUtil;

class Localize {

	private static $_localizes = array();

	private $_longDateFormat = "";
	private $_shortDateFormat = "";

	private function __construct() {
	}
	
	public function getLongDateFormat(){
		return $this->_longDateFormat;
	}
	
	public function getShortDateFormat(){
		return $this->_shortDateFormat;
	}

	public function formatDate($format, $time= null) {
		$nt = $time? $time: time();
		switch($format){
			case 'L':
				return date($this->_longDateFormat, $nt);
			case 'S':
				return date($this->_shortDateFormat, $nt);
			default:
				return date($format, $nt);
		}
	}

	public function initialize($lang) {
		// $locales = array();
		// if (!array_key_exists($lang, self::$_localizes)) {
		$locale = array();
		$file = PathUtil::combines(Configuration::$localeDirectory, $lang, '.csv');
		$lines = FileUtil::readCsv($file);
		for ($i = 0; $i < count($lines); $i++) {
			switch($lines[$i][0]) {
				case 'longDate' : {
					// $locale['longDateFormat'] = $lines[$i][1];
					$this->_longDateFormat = $lines[$i][1];
					break;
				}
				case 'shortDate' : {
					// $locale['shortDateFormat'] = $lines[$i][1];
					$this->_shortDateFormat = $lines[$i][1];
					break;
				}
			}
		}
		// $locales[$lang] = $locale;
		// }
		// $this->_longDateFormat = $locales[$lang]['longDateFormat'];
		// $this->_shortDateFormat = $locales[$lang]['shortDateFormat'];
	}

	private static $_instance = NULL;
	public static function singleton(){
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;		
	}
}
