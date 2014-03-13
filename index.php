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
	
	include_once 'tmplfunc.php';
	
	\Toys\Log\Configuration::$outputSettings = array(
		'file'=>array(
			'class'=>'\Toys\Log\Output\FileOutput',
			'folder'=>ROOT_PATH.'logs'
		)
	);
	\Toys\Log\Configuration::$defaultOutput = 'file';	
	
	\Localization\Configuration::$defaultLanguage = 'zh-CN';
	\Localization\Configuration::$languageDirectory = PRI_PATH . 'langs';
	\Localization\Configuration::$localeDirectory = PRI_PATH . 'locales';

	\Toys\Data\Configuration::addConnection('default', 'Toys\Data\Provider\MysqlProvider', 'mysql:host=localhost;dbname=comexoa', 'root', '');
	\Toys\Data\Configuration::$trace = true;

	\Toys\Framework\Configuration::addDomain('frontend', 'Frontend', '/', 'user/defend/login', TRUE);
	\Toys\Framework\Configuration::addDomain('backend', 'Backend', '/admin/', 'user/defend/login');
	
	// \Toys\Framework\Configuration::addDomain('frontend', '/', 'Frontend', 'user/guard/login', TRUE);
	// \Toys\Framework\Configuration::addDomain('backend', '/admin/', 'Backend', 'user/guard/login');
	// \Toys\Framework\Configuration::addDomain('member', '/member/', 'Member', 'index/index/index');
	\Toys\Framework\Configuration::$componentDirectories[] = PRI_PATH . 'components';
	\Toys\Framework\Configuration::$templateDirectories = array(PRI_PATH . 'templates');
	\Toys\Framework\Configuration::$templateTheme = 'default';
	// \Toys\Framework\Configuration::$language = 'zh-CN';
	\Toys\Framework\Configuration::$trace = true;

	\Toys\Framework\Application::run();
}
