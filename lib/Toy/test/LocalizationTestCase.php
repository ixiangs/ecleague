<?php
use Toy\Unit\TestCase;
use Localization\Configuration;
use Localization\Dictionary;

class LocalizationTestCase extends TestCase {
	
	public function __construct(){
		Configuration::$languageDirectory = TEST_PATH.'langs';
		Configuration::$defaultLanguage = 'zh-CN';
		Configuration::$localeDirectory = TEST_PATH.'locales';
	}

	public function testDictionary() {
		$texts = Dictionary::singleton()->initialize('zh-CN');
		$this -> assertEqual(true, count($texts) > 0);
		$this->assertEqual("world",$texts['hello']);
		$this->assertEqual("OA", $texts["website_title"]);
		$this->assertEqual("OA-OA*OA", $texts->format('%s-%s*%s', $texts['website_title'],$texts['website_title'],$texts['website_title']));
	}

	public function testLocalize(){

	}
}
