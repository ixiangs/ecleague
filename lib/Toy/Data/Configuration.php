<?php
namespace Toy\Data;

class Configuration
{

    static public $tablePrefix = '';
    static public $trace = false;
    static public $defaultConnection = 'default';
    static public $connectionSettings = array();
    static public $logger = null;

	static public function addConnection($name, $provider, $settings){
		self::$connectionSettings[$name] = array(
            'provider'=>$provider,
            'settings'=>$settings
        );
	}
}
