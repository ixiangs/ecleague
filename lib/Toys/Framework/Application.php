<?php
namespace Toys\Framework;

use Toys\Event;
use Toys\Util\ArrayUtil, Toys\Util\PathUtil, Toys\Util\FileUtil, Toys\Util\StringUtil;
use Toys\Localization\Dictionary, Toys\Localization\Localize;
use Toys\Http\Request, Toys\Http\Response, Toys\Http\Session;

class Application {

	const APPLICATION_ON_INITIALIZ = 'applicationOnInitialize';
	const APPLICATION_ON_START = 'applicationOnStart';
	const APPLICATION_PRE_ROUTE = 'applicationPreRoute';
	const APPLICATION_ON_ROUTE = 'applicationOnRoute';
	const APPLICATION_POST_ROUTE = 'applicationPostRoute';
	const APPLICATION_PRE_DISPATCH = 'applicationPreDispatch';
	const APPLICATION_ON_DISPATCH = 'applicationOnDispatch';
	const APPLICATION_POST_DISPATCH = 'applicationPostDispatch';
	const APPLICATION_PRE_OUTPUT = 'applicationPreOutput';
	const APPLICATION_ON_OUTPUT = 'applicationOnOutput';
	const APPLICATION_POST_OUTPUT = 'applicationPostOutput';
	const APPLICATION_ON_END = 'applicationOnEnd';

	private static $_instance = NULL;

	private $_context = null;

	protected function __construct() {
		$this -> _context = new Context();
		Event\Configuration::addEvent(Application::APPLICATION_ON_INITIALIZ, Application::APPLICATION_ON_START, Application::APPLICATION_PRE_ROUTE, Application::APPLICATION_POST_ROUTE, Application::APPLICATION_PRE_DISPATCH, Application::APPLICATION_ON_DISPATCH, Application::APPLICATION_POST_DISPATCH, Application::APPLICATION_PRE_OUTPUT, Application::APPLICATION_ON_OUTPUT, Application::APPLICATION_POST_OUTPUT, Application::APPLICATION_ON_END);
	}

	public function getContext() {
		return $this -> _context;
	}

	protected function initialize() {
		$this -> loadComponents();
		$this -> _context 
			-> setRequest(new Request()) 
			-> setResponse(new Response()) 
			-> setSession(new Session()) 
			-> setRouter(new Router()) 
			-> setDispatcher(new Dispatcher())
			-> setOutput(new Output());
		Event\Dispatcher::dispatch(Application::APPLICATION_ON_INITIALIZ, $this);
		return $this;
	}
	
	private function loadComponents(){
		foreach(Configuration::$componentDirectories as $path){
			PathUtil::scanRecursive($path, function($file, $info){
				if($info['basename'] == 'com.php'){
					include_once $file;
				}
			});
		}
			// FileUtil::scanCurrent($path, function($firstDir, $firstInfo){
				// $packageName = $firstInfo['filename'];
				// FileUtil::scanCurrent($firstDir, function($comDir, $comInfo) use($packageName){
					// $componentName = $comInfo['filename'];
					// foreach(Configuration::$domains as $domain){
						// $namespace = $domain->getNamespace();
						// $dp = PathUtil::combines($comDir, $domain->getNamespace());
						// if(FileUtil::isDirectory($dp)){
							// FileUtil::scanCurrent($dp, function($ctrlFile, $ctrlInfo) use($packageName, $componentName, $domain){
								// if(StringUtil::endsWith($ctrlInfo['filename'], 'Controller')){
									// include_once $ctrlFile;
									// $class = $packageName.'\\'.$componentName.'\\'.$domain->getNamespace().'\\'.$ctrlInfo['filename'];
									// $c = StringUtil::PascalCasingToDash($componentName).'/'.StringUtil::PascalCasingToDash(str_replace('Controller', '', $ctrlInfo['filename']));
									// $domain->addController($c, $class);
								// }
							// });
						// }
					// }
// 
					// if(FileUtil::checkExists($comDir.'com.php')){
						// include_once $comDir.'com.php';
					// }					
				// });
			// });
		// }
	}

	protected function start() {
		$this -> _context -> getSession() -> start();
		Event\Dispatcher::dispatch(Application::APPLICATION_ON_START, $this);
		return $this;
	}

	protected function route() {
		Event\Dispatcher::dispatch(Application::APPLICATION_PRE_ROUTE, $this);
		$s = $this -> _context -> getRouter() -> route();
		$this -> _context -> setObjective($s);
		Event\Dispatcher::dispatch(Application::APPLICATION_POST_ROUTE, $this);
		return $this;
	}

	protected function dispatch() {
		Event\Dispatcher::dispatch(Application::APPLICATION_PRE_DISPATCH, $this);
		$r = $this -> _context -> getDispatcher() -> dispatch();
		$this -> _context -> getObjective() -> setResult($r);
		Event\Dispatcher::dispatch(Application::APPLICATION_POST_DISPATCH, $this);
		return $this;
	}

	protected function output() {
		Event\Dispatcher::dispatch(Application::APPLICATION_PRE_OUTPUT, $this);
		$this->_context->getOutput() -> render();
		Event\Dispatcher::dispatch(Application::APPLICATION_POST_OUTPUT, $this);
		return $this;
	}

	protected function finish() {
		$this -> _context -> getResponse() -> flush();
		Event\Dispatcher::dispatch(Application::APPLICATION_ON_END, $this);
		exit();
	}
	
	public function quit(){
		$this->finish();
	}

	public static function singleton() {
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public static function run() {
		self::singleton() -> initialize() -> start() -> route() -> dispatch() -> output() -> finish();
	}

}
