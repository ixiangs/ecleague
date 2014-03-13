<?php
namespace Toys\Data;

class Configuration{
	
	public static $tablePrefix = '';
	public static $trace = false;
	public static $defaultConnection = 'default';
	public static $connectionSettings = array();
    public static $logger = null;
	
	public static function addConnection($name, $provider, $dsn, $user, $password, $options = array()){
		self::$connectionSettings[$name] = array(
			'name'=>$name,
			'provider'=>$provider,
			'dsn'=>$dsn,
			'user'=>$user,
			'password'=>$password,
			'options'=>$options
		);
	}
}
