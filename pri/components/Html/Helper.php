<?php
namespace Html;

class Helper{
	
	private function __construct(){}
	
	public function createForm(){
		return new Form();
	}
	
	private static $_instance = NULL;
	public static function singleton(){
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;		
	}
}
