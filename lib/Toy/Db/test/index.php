<?php
date_default_timezone_set('PRC');
define('DS', DIRECTORY_SEPARATOR);
define('Toy_PATH', strstr(dirname(__FILE__), 'Toy', true) . 'Toy' . DS);
//define('LIB_PATH', ROOT_PATH . 'lib' . DS);
//set_include_path(get_include_path() . PATH_SEPARATOR . LIB_PATH . PATH_SEPARATOR . PRI_PATH . 'components');

include_once Toy_PATH . 'Log' . DS . 'Configuration.php';
include_once Toy_PATH . 'Log' . DS . 'BaseAppender.php';
include_once Toy_PATH . 'Log' . DS . 'ConsoleAppender.php';
include_once Toy_PATH . 'Log' . DS . 'Logger.php';

include_once Toy_PATH . 'Util' . DS . 'ArrayUtil.php';

include_once Toy_PATH . 'Db' . DS . 'Configuration.php';
include_once Toy_PATH . 'Db' . DS . 'Result.php';
include_once Toy_PATH . 'Db' . DS . 'Exception.php';
include_once Toy_PATH . 'Db' . DS . 'BaseStatement.php';
include_once Toy_PATH . 'Db' . DS . 'WhereStatement.php';
include_once Toy_PATH . 'Db' . DS . 'InsertStatement.php';
include_once Toy_PATH . 'Db' . DS . 'UpdateStatement.php';
include_once Toy_PATH . 'Db' . DS . 'DeleteStatement.php';
include_once Toy_PATH . 'Db' . DS . 'SelectStatement.php';
include_once Toy_PATH . 'Db' . DS . 'Driver' . DS . 'BaseDriver.php';
include_once Toy_PATH . 'Db' . DS . 'Driver' . DS . 'PdoDriver.php';

include_once Toy_PATH . 'Unit' . DS . 'TestCase.php';
include_once Toy_PATH . 'Unit' . DS . 'AssertException.php';
include_once Toy_PATH . 'Unit' . DS . 'Runner.php';

\Toy\Db\Configuration::$logger = \Toy\Log\Logger::singleton();
\Toy\Db\Configuration::$trace = true;

\Toy\Unit\Runner::run(array(
        'directory' => Toy_PATH . 'Db' . DS . 'test',
        'output' => 'console')
);
