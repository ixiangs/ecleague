<?php
date_default_timezone_set('PRC');
define('DS', DIRECTORY_SEPARATOR);
define('ROOT_PATH', dirname(__FILE__).DS);
define('LIB_PATH', ROOT_PATH.'lib'.DS);
set_include_path(get_include_path() . PATH_SEPARATOR . LIB_PATH. PATH_SEPARATOR . PRI_PATH.'components');

//include_once LIB_PATH.DS.'Toys'.DS.'Log'.DS.'Configuration.php';
include_once LIB_PATH.DS.'Toys'.DS.'Data'.DS.'Configuration.php';
include_once LIB_PATH.DS.'Toys'.DS.'Data'.DS.'Result.php';
include_once LIB_PATH.DS.'Toys'.DS.'Data'.DS.'Db'.DS.'BaseProvider.php';
include_once LIB_PATH.DS.'Toys'.DS.'Data'.DS.'Db'.DS.'PdoProvider.php';
include_once LIB_PATH.DS.'Toys'.DS.'Data'.DS.'Db'.DS.'SqliteProvider.php';
include_once LIB_PATH.DS.'Toys'.DS.'Data'.DS.'Db'.DS.'BaseProvider.php';
include_once LIB_PATH.DS.'Toys'.DS.'Data'.DS.'Sql'.DS.'BaseStatement.php';
include_once LIB_PATH.DS.'Toys'.DS.'Data'.DS.'Sql'.DS.'WhereStatement.php';
include_once LIB_PATH.DS.'Toys'.DS.'Data'.DS.'Sql'.DS.'InsertStatement.php';
include_once LIB_PATH.DS.'Toys'.DS.'Data'.DS.'Sql'.DS.'UpdateStatement.php';
include_once LIB_PATH.DS.'Toys'.DS.'Data'.DS.'Sql'.DS.'DeleteStatement.php';

include_once LIB_PATH.DS.'Toys'.DS.'Unit'.DS.'TestCase.php';
include_once LIB_PATH.DS.'Toys'.DS.'Unit'.DS.'AssertException.php';
include_once LIB_PATH.DS.'Toys'.DS.'Unit'.DS.'Runner.php';

use Toys\Log\Configuration;
use Toys\Data\Configuration;
use \Toys\Unit\Runner;

\Toys\Data\Configuration::$trace = true;
\Toys\Data\Configuration::addConnection(
    'default', 'Toys\Data\Db\SqliteProvider', array('dsn'=>'')
//    'default', 'Toys\Data\Provider\MysqlProvider', array('mysql:host=localhost;dbname=comexoa', 'root', '')
);

\Toys\Unit\Runner::runFile(array('output'=>'console'));
