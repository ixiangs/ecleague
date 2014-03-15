<?php
const PAGINATION_SIZE = 50;
const PAGINATION_RANGE = 10;

if (preg_match('/\.(?:png|jpg|jpeg|gif|css|js|ico|html)$/', $_SERVER["REQUEST_URI"])) {
    return false;
} else {
    date_default_timezone_set('PRC');
    define('DS', DIRECTORY_SEPARATOR);
    define('ROOT_PATH', dirname(__FILE__) . DS);
    define('PRI_PATH', ROOT_PATH . 'pri' . DS);
    define('LIB_PATH', ROOT_PATH . 'lib' . DS);
    define('TEMP_PATH', PRI_PATH . 'temp' . DS);
    set_include_path(get_include_path() . PATH_SEPARATOR . LIB_PATH . PATH_SEPARATOR . PRI_PATH . DS . 'components');

    include_once 'Toys\Autoload.php';

    \Toys\Autoload::register();

//	include_once 'tmplfunc.php';

    \Toys\Log\Configuration::$settings = array('directory' => ROOT_PATH . 'logs');
    \Toys\Log\Configuration::$appender = '\Toys\Log\FileAppender';

//	\Localization\Configuration::$defaultLanguage = 'zh-CN';
//	\Localization\Configuration::$languageDirectory = PRI_PATH . 'langs';
//	\Localization\Configuration::$localeDirectory = PRI_PATH . 'locales';

    \Toys\Data\Configuration::$trace = true;
    \Toys\Data\Configuration::$logger = \Toys\Log\Logger::singleton();
    \Toys\Data\Configuration::addConnection('default', 'Toys\Data\Provider\MysqlProvider', 'mysql:host=localhost;dbname=comexoa', 'root', '');

    \Toys\Web\Configuration::$trace = true;
    \Toys\Web\Configuration::$componentDirectories = array(PRI_PATH . 'components');
    \Toys\Web\Configuration::$templateDirectories = array(PRI_PATH . 'templates');
    \Toys\Web\Configuration::$templateTheme = 'default';
    \Toys\Web\Configuration::$logger = \Toys\Log\Logger::singleton();
    \Toys\Web\Configuration::addDomain('frontend', 'Frontend', '/', TRUE);
    \Toys\Web\Configuration::addDomain('backend', 'Backend', '/admin/');
    // \Toys\Web\Configuration::$language = 'zh-CN';

    \Toys\Web\Application::run();
}
