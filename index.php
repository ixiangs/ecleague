<?php
const PAGINATION_SIZE = 20;
const PAGINATION_RANGE = 10;

if (preg_match('/^\/pub\/|\.html$/', $_SERVER["REQUEST_URI"])) {
    return false;
} else {
    date_default_timezone_set('PRC');
    define('DS', DIRECTORY_SEPARATOR);
    define('ROOT_PATH', dirname(__FILE__) . DS);
    define('PRI_PATH', ROOT_PATH . 'pri' . DS);
    define('CODE_PATH', ROOT_PATH . 'code' . DS);
    define('CONF_PATH', ROOT_PATH . 'conf' . DS);
    define('LIB_PATH', ROOT_PATH . 'lib' . DS);
    define('TEMP_PATH', PRI_PATH . 'temp' . DS);
    set_include_path(get_include_path() . PATH_SEPARATOR . LIB_PATH . PATH_SEPARATOR . CODE_PATH);

    include_once 'Toy\Platform\FileUtil.php';
    include_once 'Toy\Platform\PathUtil.php';
    include_once 'Toy\Autoload.php';
    include_once 'Toy\Loader.php';

    \Toy\Autoload::register();

    \Toy\Loader::$path = CODE_PATH;
    \Toy\Loader::$namespaces = array('Core');

//	include_once 'tmplfunc.php';

    \Toy\Log\Configuration::$settings = array('directory' => ROOT_PATH . 'log');
    \Toy\Log\Configuration::$appender = '\Toy\Log\FileAppender';

    \Toy\Db\Configuration::$trace = true;
    \Toy\Db\Configuration::$logger = \Toy\Log\Logger::singleton();
    \Toy\Db\Configuration::addConnection('default', 'Toy\Db\SqliteProvider', array(
        'dsn' => 'sqlite:' . ROOT_PATH . 'db.db'
    ));

    \Toy\Web\Configuration::$initializerClass = '\Ecleague\Initializer';
    \Toy\Web\Configuration::$handlerClass = '\Ecleague\Handler';
    \Toy\Web\Configuration::$routerClass = '\Ecleague\Router';
    \Toy\Web\Configuration::$rendererClass = '\Ecleague\Renderer';
    \Toy\Web\Configuration::$trace = true;
    \Toy\Web\Configuration::$logger = \Toy\Log\Logger::singleton();

    \Ecleague\Configuration::addDomain('frontend', 'Frontend', '/', TRUE);
    \Ecleague\Configuration::addDomain('backend', 'Backend', '/admin/');

    \Toy\View\Configuration::$trace = true;
    \Toy\View\Configuration::$templateRoot = PRI_PATH . 'templates';
    \Toy\View\Configuration::$templateDirectories = array(PRI_PATH . 'templates');
    \Toy\View\Configuration::$logger = \Toy\Log\Logger::singleton();

    \Toy\Web\Application::run();
}
