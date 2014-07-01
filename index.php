<?php
const PAGINATION_SIZE = 20;
const PAGINATION_RANGE = 10;

if (preg_match('/^\/pub\/|\.ico|\.html$/', $_SERVER["REQUEST_URI"])) {
    return false;
} else {
    date_default_timezone_set('PRC');
    define('DS', DIRECTORY_SEPARATOR);
    define('ROOT_PATH', dirname(__FILE__) . DS);
    define('TEMPLATE_PATH', ROOT_PATH . 'templates' . DS);
    define('CODE_PATH', ROOT_PATH . 'code' . DS);
    define('LIBRARY_PATH', ROOT_PATH . 'libraries' . DS);
    define('TEMP_PATH', ROOT_PATH . 'temp' . DS);
    define('JS_URL', '/pub/assets/js/');
    define('CSS_URL', '/pub/assets/css/');
    define('IMG_URL', '/pub/assets/img/');
    set_include_path(get_include_path() . PATH_SEPARATOR . LIBRARY_PATH . PATH_SEPARATOR, CODE_PATH);

    include_once 'Toy\Platform\FileUtil.php';
    include_once 'Toy\Platform\PathUtil.php';
    include_once 'Toy\Autoload.php';

    \Toy\Autoload::register();

    \Toy\Log\Configuration::$settings = array('directory' => ROOT_PATH . 'log');
    \Toy\Log\Configuration::$appender = '\Toy\Log\FileAppender';

    \Toy\Orm\Configuration::$trace = true;
    \Toy\Orm\Configuration::addConnection('default', array(
        'dsn' => 'sqlite:' . ROOT_PATH . 'db.db'
    ));

    \Toy\Web\Configuration::$trace = true;
    \Toy\Web\Configuration::addDomain('frontend', 'Frontend', '/', '/', '/', TRUE);
    \Toy\Web\Configuration::addDomain('backend', 'Backend', '/admin/', 'ixiangs_admin/main/dashboard', 'ixiangs_admin/account/login');

    \Toy\View\Configuration::$trace = true;
    \Toy\View\Configuration::$templateRoot = TEMPLATE_PATH;

    \Toy\Web\Application::run();
}
