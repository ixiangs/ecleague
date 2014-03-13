<?php
date_default_timezone_set('PRC');
define('DS', DIRECTORY_SEPARATOR);
define('TEST_PATH', dirname(__FILE__) . DS);
define('TOYS_PATH', str_replace('Toys', '', str_replace('test', '', dirname(__FILE__))));
set_include_path(get_include_path() . PATH_SEPARATOR . TOYS_PATH . PATH_SEPARATOR . TEST_PATH);

include_once 'Toys\Autoload.php';

\Toys\Autoload::register();

\Toys\Log\Configuration::$outputSettings = array(
	'console'=>array(
		'class'=>'\Toys\Log\Output\ConsoleOutput'
	)
);
\Toys\Log\Configuration::$defaultOutput = 'console';

\Toys\Unit\Runner::run(array('directory' => TEST_PATH, 'output'=>'console'));
