<?php
date_default_timezone_set('PRC');
define('DS', DIRECTORY_SEPARATOR);
define('ROOT_PATH', dirname(__FILE__).DS);
define('PRI_PATH', ROOT_PATH.'pri'.DS);
define('LIB_PATH', ROOT_PATH.'lib'.DS);
define('TEST_PATH', ROOT_PATH.'test');
set_include_path(get_include_path() . PATH_SEPARATOR . LIB_PATH. PATH_SEPARATOR . PRI_PATH.'components');

include_once 'Toys\Autoload.php';
\Toys\Autoload::register();

\Toys\Log\Configuration::$outputSettings = array(
	'console'=>array(
		'class'=>'\Toys\Log\Output\ConsoleOutput'
	)
);
\Toys\Log\Configuration::$defaultOutput = 'console';

\Toys\Data\Configuration::addConnection('default', 'Toys\Data\Provider\MysqlProvider', 'mysql:host=localhost;dbname=comexoa', 'root', '');
\Toys\Data\Configuration::$trace = true;

\Toys\Unit\Runner::run(array('directory' => TEST_PATH, 'output'=>'console'));
