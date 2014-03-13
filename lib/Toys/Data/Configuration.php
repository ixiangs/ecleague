<?php
namespace Toys\Data;

class Configuration
{

    public static $tablePrefix = '';
    public static $trace = false;
    public static $defaultConnection = 'default';
    public static $connectionSettings = array();
    public static $logger = null;

	public static function addConnection($name, $provider, $settings){
		self::$connectionSettings[$name] = array(
            'provider'=>$provider,
            'settings'=>$settings
        );
	}
}
