<?php
namespace Toy\Web;

use Toy\Event;
use Toy\Platform\FileUtil, Toy\Platform\PathUtil;
use Toy\Http\Request, Toy\Http\Response, Toy\Http\Session;

class Application
{

    const APPLICATION_ON_INITIALIZ = 'applicationOnInitialize';
    const APPLICATION_ON_START = 'applicationOnStart';
    const APPLICATION_PRE_ROUTE = 'applicationPreRoute';
    const APPLICATION_POST_ROUTE = 'applicationPostRoute';
    const APPLICATION_PRE_HANDLER = 'applicationPreHandler';
    const APPLICATION_POST_HANDLER = 'applicationPostHandler';
    const APPLICATION_PRE_RENDER = 'applicationPreRender';
    const APPLICATION_POST_RENDER = 'applicationPostRender';
    const APPLICATION_ON_END = 'applicationOnEnd';

    private static $_instance = NULL;

    public static $componentSettings = array();

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
            Application::APPLICATION_POST_HANDLER,
            Application::APPLICATION_PRE_RENDER,
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
//        foreach (Configuration::$codeDirectory as $path) {
        PathUtil::scanRecursive(Configuration::$codeDirectory, function ($file, $info) {
            if ($info['basename'] == 'conf.json') {
                $conf = FileUtil::readJson($file);
                self::$componentSettings[$conf['name']] = $conf;
            }
        });
//        }
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
        $this->_context->result = $this->_context->handler->handle();
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
