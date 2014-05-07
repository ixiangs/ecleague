<?php
const PAGINATION_SIZE = 20;
const PAGINATION_RANGE = 10;

if (preg_match('/^\/pub\/|\.html$/', $_SERVER["REQUEST_URI"])) {
    return false;
} else {
    date_default_timezone_set('PRC');
    define('DS', DIRECTORY_SEPARATOR);
    define('ROOT_PATH', dirname(__FILE__) . DS);
    define('VIEW_PATH', ROOT_PATH . 'views' . DS);
    define('CONTROLLER_PATH', ROOT_PATH . 'controllers' . DS);
    define('COMPONENT_PATH', ROOT_PATH . 'components' . DS);
    define('LIB_PATH', ROOT_PATH . 'lib' . DS);
    define('TEMP_PATH', ROOT_PATH . 'temp' . DS);
    set_include_path(get_include_path() . PATH_SEPARATOR . LIB_PATH . PATH_SEPARATOR . COMPONENT_PATH);

    include_once 'Toy\Platform\FileUtil.php';
    include_once 'Toy\Platform\PathUtil.php';
    include_once 'Toy\Autoload.php';

    \Toy\Autoload::register();

    \Toy\Log\Configuration::$settings = array('directory' => ROOT_PATH . 'log');
    \Toy\Log\Configuration::$appender = '\Toy\Log\FileAppender';

    \Toy\Db\Configuration::$trace = true;
    \Toy\Db\Configuration::addConnection('default', array(
        'dsn' => 'sqlite:' . ROOT_PATH . 'db.db'
    ));

    \Toy\Web\Configuration::$trace = true;
    \Toy\Web\Configuration::$controllerDirectory = CONTROLLER_PATH;
    \Toy\Web\Configuration::$componentDirectory = COMPONENT_PATH;
    \Toy\Web\Configuration::addDomain('frontend', '/', '/', '/', TRUE);
    \Toy\Web\Configuration::addDomain('backend', '/admin/', 'ixiangs_admin/main/dashboard', 'ixiangs_admin/account/login');

    \Toy\View\Configuration::$trace = true;
    \Toy\View\Configuration::$templateRoot = VIEW_PATH;

    \Toy\Web\Application::run();
}
