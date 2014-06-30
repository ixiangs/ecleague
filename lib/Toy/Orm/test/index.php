<?php
date_default_timezone_set('PRC');
define('DS', DIRECTORY_SEPARATOR);
define('Toy_PATH', strstr(dirname(__FILE__), 'Toy', true) . 'Toy' . DS);
//define('LIB_PATH', ROOT_PATH . 'lib' . DS);
//set_include_path(get_include_path() . PATH_SEPARATOR . LIB_PATH . PATH_SEPARATOR . PRI_PATH . 'components');

include_once Toy_PATH . 'Object.php';
include_once Toy_PATH . 'Log' . DS . 'Configuration.php';
include_once Toy_PATH . 'Log' . DS . 'BaseAppender.php';
include_once Toy_PATH . 'Log' . DS . 'ConsoleAppender.php';
include_once Toy_PATH . 'Log' . DS . 'Logger.php';

include_once Toy_PATH . 'Util' . DS . 'ArrayUtil.php';

include_once Toy_PATH . 'Collection' . DS . 'TEnumerator.php';
include_once Toy_PATH . 'Collection' . DS . 'TList.php';

include_once Toy_PATH . 'Orm' . DS . 'Db' . DS . 'Result.php';
include_once Toy_PATH . 'Orm' . DS . 'Db' . DS . 'Exception.php';
include_once Toy_PATH . 'Orm' . DS . 'Db' . DS . 'BaseStatement.php';
include_once Toy_PATH . 'Orm' . DS . 'Db' . DS . 'WhereStatement.php';
include_once Toy_PATH . 'Orm' . DS . 'Db' . DS . 'InsertStatement.php';
include_once Toy_PATH . 'Orm' . DS . 'Db' . DS . 'UpdateStatement.php';
include_once Toy_PATH . 'Orm' . DS . 'Db' . DS . 'DeleteStatement.php';
include_once Toy_PATH . 'Orm' . DS . 'Db' . DS . 'SelectStatement.php';
include_once Toy_PATH . 'Orm' . DS . 'Db' . DS . 'Driver' . DS . 'BaseDriver.php';
include_once Toy_PATH . 'Orm' . DS . 'Db' . DS . 'Driver' . DS . 'PdoDriver.php';


include_once Toy_PATH . 'Orm' . DS . 'Metadata.php';
include_once Toy_PATH . 'Orm' . DS . 'Helper.php';
include_once Toy_PATH . 'Orm' . DS . 'Configuration.php';
include_once Toy_PATH . 'Orm' . DS . 'BaseProperty.php';
include_once Toy_PATH . 'Orm' . DS . 'IntegerProperty.php';
include_once Toy_PATH . 'Orm' . DS . 'StringProperty.php';
include_once Toy_PATH . 'Orm' . DS . 'BooleanProperty.php';
include_once Toy_PATH . 'Orm' . DS . 'EmailProperty.php';
include_once Toy_PATH . 'Orm' . DS . 'DateTimeProperty.php';
include_once Toy_PATH . 'Orm' . DS . 'FloatProperty.php';
include_once Toy_PATH . 'Orm' . DS . 'SerializeProperty.php';
include_once Toy_PATH . 'Orm' . DS . 'Queryable.php';
include_once Toy_PATH . 'Orm' . DS . 'Model.php';

include_once Toy_PATH . 'Unit' . DS . 'TestCase.php';
include_once Toy_PATH . 'Unit' . DS . 'AssertException.php';
include_once Toy_PATH . 'Unit' . DS . 'Runner.php';

\Toy\Orm\Configuration::$logger = \Toy\Log\Logger::singleton();
\Toy\Orm\Configuration::$trace = true;
\Toy\Orm\Configuration::addConnection(
    'default', array('dsn' => 'sqlite:' . dirname(__FILE__) . DS . 'testdb.db3')
//    'default', 'Toy\Db\Provider\MysqlProvider', array('mysql:host=localhost;dbname=comexoa', 'root', '')
);

\Toy\Unit\Runner::run(array(
        'directory' => Toy_PATH . 'Orm' . DS . 'test',
        'output' => 'console'
));
