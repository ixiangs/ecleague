<?php
namespace Toy\Web;

use Toy\Event;
use Toy\Util\ArrayUtil, Toy\Util\PathUtil, Toy\Util\FileUtil, Toy\Util\StringUtil;
//use Toy\Localization\Dictionary, Toy\Localization\Localize;
use Toy\Http\Request, Toy\Http\Response, Toy\Http\Session;

class Application
{

    const APPLICATION_ON_INITIALIZ = 'applicationOnInitialize';
    const APPLICATION_ON_START = 'applicationOnStart';
    const APPLICATION_PRE_ROUTE = 'applicationPreRoute';
//    const APPLICATION_ON_ROUTE = 'applicationOnRoute';
    const APPLICATION_POST_ROUTE = 'applicationPostRoute';
    const APPLICATION_PRE_HANDLER = 'applicationPreHandler';
//    const APPLICATION_ON_HANDLER = 'applicationOnHandler';
    const APPLICATION_POST_HANDLER = 'applicationPostHandler';
    const APPLICATION_PRE_RENDER = 'applicationPreRender';
//    const APPLICATION_ON_RENDER = 'applicationOnRender';
    const APPLICATION_POST_RENDER = 'applicationPostRender';
    const APPLICATION_ON_END = 'applicationOnEnd';

    private static $_instance = NULL;

    private $_context = null;

    protected function __construct()
    {
        $this->_context = new Context();
        Event\Configuration::addEvent(
            Application::APPLICATION_ON_INITIALIZ,
            Application::APPLICATION_ON_START,
            Application::APPLICATION_PRE_ROUTE,
            Application::APPLICATION_POST_ROUTE,
            Application::APPLICATION_PRE_HANDLER,
//            Application::APPLICATION_ON_HANDLER,
            Application::APPLICATION_POST_HANDLER,
            Application::APPLICATION_PRE_RENDER,
//            Application::APPLICATION_ON_RENDER,
            Application::APPLICATION_POST_RENDER,
            Application::APPLICATION_ON_END);
    }

    public function getContext()
    {
        return $this->_context;
    }

    protected function initialize()
    {
        $this->loadComponents();
        $this->_context->request = new Request();
        $this->_context->response = new Response();
        $this->_context->session = new Session();
        $this->_context->router = new Router();
        $this->_context->handler = new Handler();
        $this->_context->renderer = new Renderer();
        Event\Dispatcher::dispatch(Application::APPLICATION_ON_INITIALIZ, $this);
        return $this;
    }

    private function loadComponents()
    {
//        foreach (Configuration::$componentDirectories as $path) {
//            PathUtil::scanRecursive($path, function ($file, $info) {
//                if ($info['basename'] == 'com.php') {
//                    include_once $file;
//                }
//            });
//        }
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

    protected function start()
    {
        $this->_context->session->start();
        Event\Dispatcher::dispatch(Application::APPLICATION_ON_START, $this);
        return $this;
    }

    protected function route()
    {
        Event\Dispatcher::dispatch(Application::APPLICATION_PRE_ROUTE, $this);
        $this->_context->router->route();
        Event\Dispatcher::dispatch(Application::APPLICATION_POST_ROUTE, $this);
        return $this;
    }

    protected function handle()
    {
        Event\Dispatcher::dispatch(Application::APPLICATION_PRE_HANDLER, $this);
        $this->_context->handler->handle();
        Event\Dispatcher::dispatch(Application::APPLICATION_POST_HANDLER, $this);
        return $this;
    }

    protected function render()
    {
        Event\Dispatcher::dispatch(Application::APPLICATION_PRE_RENDER, $this);
        $this->_context->renderer->render();
        Event\Dispatcher::dispatch(Application::APPLICATION_POST_RENDER, $this);
        return $this;
    }

    protected function finish()
    {
        $this->_context->response->flush();
        Event\Dispatcher::dispatch(Application::APPLICATION_ON_END, $this);
        exit();
    }

    public function quit()
    {
        $this->finish();
    }

    public static function singleton()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public static function run()
    {
        self::singleton()->initialize()->start()->route()->handle()->render()->finish();
    }

}
