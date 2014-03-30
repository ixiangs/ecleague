<?php
const PAGINATION_SIZE = 20;
const PAGINATION_RANGE = 10;

if (preg_match('/\.(?:png|jpg|jpeg|gif|css|js|ico|html)$/', $_SERVER["REQUEST_URI"])) {
    return false;
} else {
    date_default_timezone_set('PRC');
    define('DS', DIRECTORY_SEPARATOR);
    define('ROOT_PATH', dirname(__FILE__) . DS);
    define('PRI_PATH', ROOT_PATH . 'pri' . DS);
    define('CODE_PATH', ROOT_PATH . 'code' . DS);
    define('LIB_PATH', ROOT_PATH . 'lib' . DS);
    define('TEMP_PATH', PRI_PATH . 'temp' . DS);
    set_include_path(get_include_path() . PATH_SEPARATOR . LIB_PATH . PATH_SEPARATOR . CODE_PATH);

    include_once 'Toy\Platform\FileUtil.php';
    include_once 'Toy\Platform\PathUtil.php';
    include_once 'Toy\Autoload.php';

    \Toy\Autoload::$codePath = CODE_PATH;
    \Toy\Autoload::register();

//	include_once 'tmplfunc.php';

    \Toy\Log\Configuration::$settings = array('directory' => ROOT_PATH . 'log');
    \Toy\Log\Configuration::$appender = '\Toy\Log\FileAppender';

    \Toy\Data\Configuration::$trace = true;
    \Toy\Data\Configuration::$logger = \Toy\Log\Logger::singleton();
    \Toy\Data\Configuration::addConnection('default', 'Toy\Data\Db\SqliteProvider', array(
        'dsn' => 'sqlite:' . ROOT_PATH . 'db.db'
    ));

    \Toy\Web\Configuration::$trace = true;
    \Toy\Web\Configuration::$codeDirectory = CODE_PATH;
    \Toy\Web\Configuration::$templateDirectories = array(PRI_PATH . 'templates');
    \Toy\Web\Configuration::$templateTheme = 'default';
    \Toy\Web\Configuration::$logger = \Toy\Log\Logger::singleton();
    \Toy\Web\Configuration::addDomain('frontend', 'Frontend', '/', TRUE);
    \Toy\Web\Configuration::addDomain('backend', 'Backend', '/admin/');

    \Toy\Web\Application::run();
}
