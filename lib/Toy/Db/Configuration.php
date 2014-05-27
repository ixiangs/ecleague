<?php
namespace Toy\Db;

class Configuration
{

    static public $tablePrefix = '';
    static public $trace = false;
    static public $driverClass = '\Toy\Db\Driver\PdoDriver';
    static public $defaultConnection = 'default';
    static public $connectionSettings = array();
    static public $logger = null;

    static public function addConnection($name, $settings, $default = true)
    {
        self::$connectionSettings[$name] = $settings;
        if ($default) {
            self::$defaultConnection = $name;
        }
    }
}
