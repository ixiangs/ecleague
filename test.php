<?php
date_default_timezone_set('PRC');
define('DS', DIRECTORY_SEPARATOR);
define('ROOT_PATH', dirname(__FILE__).DS);
define('PRI_PATH', ROOT_PATH.'pri'.DS);
define('LIB_PATH', ROOT_PATH.'lib'.DS);
define('TEST_PATH', ROOT_PATH.'test');
set_include_path(get_include_path() . PATH_SEPARATOR . LIB_PATH. PATH_SEPARATOR . PRI_PATH.'components');

include_once 'Toy\Autoload.php';
\Toy\Autoload::register();

\Toy\Log\Configuration::$outputSettings = array(
	'console'=>array(
		'class'=>'\Toy\Log\Output\ConsoleOutput'
	)
);
\Toy\Log\Configuration::$defaultOutput = 'console';

\Toy\Data\Configuration::addConnection('default', 'Toy\Data\Provider\MysqlProvider', 'mysql:host=localhost;dbname=comexoa', 'root', '');
\Toy\Data\Configuration::$trace = true;

\Toy\Unit\Runner::run(array('directory' => TEST_PATH, 'output'=>'console'));
