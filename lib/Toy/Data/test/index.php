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

include_once Toy_PATH . 'Data' . DS . 'Configuration.php';
include_once Toy_PATH . 'Data' . DS . 'Result.php';
include_once Toy_PATH . 'Data' . DS . 'Exception.php';
include_once Toy_PATH . 'Data' . DS . 'Db' . DS . 'BaseProvider.php';
include_once Toy_PATH . 'Data' . DS . 'Db' . DS . 'PdoProvider.php';
include_once Toy_PATH . 'Data' . DS . 'Db' . DS . 'SqliteProvider.php';
include_once Toy_PATH . 'Data' . DS . 'Db' . DS . 'BaseProvider.php';
include_once Toy_PATH . 'Data' . DS . 'Sql' . DS . 'BaseStatement.php';
include_once Toy_PATH . 'Data' . DS . 'Sql' . DS . 'WhereStatement.php';
include_once Toy_PATH . 'Data' . DS . 'Sql' . DS . 'InsertStatement.php';
include_once Toy_PATH . 'Data' . DS . 'Sql' . DS . 'UpdateStatement.php';
include_once Toy_PATH . 'Data' . DS . 'Sql' . DS . 'DeleteStatement.php';
include_once Toy_PATH . 'Data' . DS . 'Sql' . DS . 'SelectStatement.php';

include_once Toy_PATH . 'Unit' . DS . 'TestCase.php';
include_once Toy_PATH . 'Unit' . DS . 'AssertException.php';
include_once Toy_PATH . 'Unit' . DS . 'Runner.php';

\Toy\Data\Configuration::$logger = \Toy\Log\Logger::singleton();
\Toy\Data\Configuration::$trace = true;
\Toy\Data\Configuration::addConnection(
    'default', 'Toy\Data\Db\SqliteProvider', array('dsn' => 'sqlite:'.dirname(__FILE__).DS.'testdb.db3')
//    'default', 'Toy\Data\Provider\MysqlProvider', array('mysql:host=localhost;dbname=comexoa', 'root', '')
);

\Toy\Unit\Runner::run(array(
        'directory' => Toy_PATH . 'Data' . DS . 'test',
        'output' => 'console')
);
