<?php
if (preg_match('/^\/static\/|^\/assets\/|\.ico|\.html$/', $_SERVER["REQUEST_URI"])) {
    return false;
} else {
    date_default_timezone_set('PRC');
    define('PAGINATION_SIZE', 20);
    define('PAGINATION_RANGE', 10);
    define('DS', DIRECTORY_SEPARATOR);
    define('ROOT_PATH', dirname(__FILE__) . DS);
    define('TEMPLATE_PATH', ROOT_PATH . 'templates' . DS);
    define('CODE_PATH', ROOT_PATH . 'codes' . DS);
    define('LIBRARY_PATH', ROOT_PATH . 'libraries' . DS);
    define('TMP_PATH', ROOT_PATH . 'tmp' . DS);
    define('ASSET_PATH', ROOT_PATH . 'assets' . DS);
    define('STATIC_URL', '/static/');
    define('JS_URL', '/static/js/');
    define('CSS_URL', '/static/css/');
    define('IMG_URL', '/static/img/');
    set_include_path(get_include_path() . PATH_SEPARATOR . LIBRARY_PATH . PATH_SEPARATOR . CODE_PATH);

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
    \Toy\Web\Configuration::$languagePath = ROOT_PATH . 'languages' . DS;
    \Toy\Web\Configuration::$templateRoot = TEMPLATE_PATH;
    \Toy\Web\Configuration::$componentDirectory = ROOT_PATH . 'codes';
    \Toy\Web\Configuration::addDomain('frontend', 'Frontend', '/', '/', '/', false, true);
    \Toy\Web\Configuration::addDomain('mobile', 'Frontend', '/mobile/', 'void_index/index/index', 'void_index/index/index', false);
    \Toy\Web\Configuration::addDomain('member', 'Member', '/member/', 'void_index/index/index', 'void_index/passport/login', true);
    \Toy\Web\Configuration::addDomain('backend', 'Backend', '/backend/', 'void_index/index/index', 'void_index/passport/login', true);

    \Toy\Web\Application::run();
}
