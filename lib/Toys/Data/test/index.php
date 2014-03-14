<?php
date_default_timezone_set('PRC');
define('DS', DIRECTORY_SEPARATOR);
define('TOYS_PATH', strstr(dirname(__FILE__), 'Toys', true) . 'Toys' . DS);
//define('LIB_PATH', ROOT_PATH . 'lib' . DS);
//set_include_path(get_include_path() . PATH_SEPARATOR . LIB_PATH . PATH_SEPARATOR . PRI_PATH . 'components');

include_once TOYS_PATH . 'Log' . DS . 'Configuration.php';
include_once TOYS_PATH . 'Log' . DS . 'BaseAppender.php';
include_once TOYS_PATH . 'Log' . DS . 'ConsoleAppender.php';
include_once TOYS_PATH . 'Log' . DS . 'Logger.php';

include_once TOYS_PATH . 'Data' . DS . 'Configuration.php';
include_once TOYS_PATH . 'Data' . DS . 'Result.php';
include_once TOYS_PATH . 'Data' . DS . 'Exception.php';
include_once TOYS_PATH . 'Data' . DS . 'Db' . DS . 'BaseProvider.php';
include_once TOYS_PATH . 'Data' . DS . 'Db' . DS . 'PdoProvider.php';
include_once TOYS_PATH . 'Data' . DS . 'Db' . DS . 'SqliteProvider.php';
include_once TOYS_PATH . 'Data' . DS . 'Db' . DS . 'BaseProvider.php';
include_once TOYS_PATH . 'Data' . DS . 'Sql' . DS . 'BaseStatement.php';
include_once TOYS_PATH . 'Data' . DS . 'Sql' . DS . 'WhereStatement.php';
include_once TOYS_PATH . 'Data' . DS . 'Sql' . DS . 'InsertStatement.php';
include_once TOYS_PATH . 'Data' . DS . 'Sql' . DS . 'UpdateStatement.php';
include_once TOYS_PATH . 'Data' . DS . 'Sql' . DS . 'DeleteStatement.php';
include_once TOYS_PATH . 'Data' . DS . 'Sql' . DS . 'SelectStatement.php';

include_once TOYS_PATH . 'Unit' . DS . 'TestCase.php';
include_once TOYS_PATH . 'Unit' . DS . 'AssertException.php';
include_once TOYS_PATH . 'Unit' . DS . 'Runner.php';

\Toys\Data\Configuration::$logger = \Toys\Log\Logger::singleton();
\Toys\Data\Configuration::$trace = true;
\Toys\Data\Configuration::addConnection(
    'default', 'Toys\Data\Db\SqliteProvider', array('dsn' => 'sqlite:'.dirname(__FILE__).DS.'testdb.db3')
//    'default', 'Toys\Data\Provider\MysqlProvider', array('mysql:host=localhost;dbname=comexoa', 'root', '')
);

\Toys\Unit\Runner::run(array(
        'directory' => TOYS_PATH . 'Data' . DS . 'test',
        'output' => 'console')
);
