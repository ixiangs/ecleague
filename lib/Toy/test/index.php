<?php
date_default_timezone_set('PRC');
define('DS', DIRECTORY_SEPARATOR);
define('TEST_PATH', dirname(__FILE__) . DS);
define('Toy_PATH', str_replace('Toy', '', str_replace('test', '', dirname(__FILE__))));
set_include_path(get_include_path() . PATH_SEPARATOR . Toy_PATH . PATH_SEPARATOR . TEST_PATH);

include_once 'Toy\Autoload.php';

\Toy\Autoload::register();

\Toy\Log\Configuration::$outputSettings = array(
	'console'=>array(
		'class'=>'\Toy\Log\Output\ConsoleOutput'
	)
);
\Toy\Log\Configuration::$defaultOutput = 'console';

\Toy\Unit\Runner::run(array('directory' => TEST_PATH, 'output'=>'console'));
