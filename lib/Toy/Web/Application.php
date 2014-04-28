<?php
namespace Toy\Web;

use Toy\Event;
use Toy\Platform\FileUtil, Toy\Platform\PathUtil;
use Toy\Http\Request, Toy\Http\Response, Toy\Http\Session;

class Application
{

    const WEB_ON_INITIALIZ = 'webOnInitialize';
    const WEB_ON_START = 'webOnStart';
    const WEB_PRE_ROUTE = 'webPreRoute';
    const WEB_POST_ROUTE = 'webPostRoute';
    const WEB_PRE_HANDLER = 'webPreHandler';
    const WEB_POST_HANDLER = 'webPostHandler';
    const WEB_PRE_RENDER = 'webPreRender';
    const WEB_POST_RENDER = 'webPostRender';
    const WEB_ON_END = 'webOnEnd';

    protected function __construct()
    {
        Event\Configuration::addEvent(
            Application::WEB_ON_INITIALIZ,
            Application::WEB_ON_START,
            Application::WEB_PRE_ROUTE,
            Application::WEB_POST_ROUTE,
            Application::WEB_PRE_HANDLER,
            Application::WEB_POST_HANDLER,
            Application::WEB_PRE_RENDER,
            Application::WEB_POST_RENDER,
            Application::WEB_ON_END);
    }

    protected function initialize()
    {
        $cls = Configuration::$initializerClass;
        $initer = new $cls();
        $initer->initialize();
        Event\Dispatcher::dispatch(Application::WEB_ON_INITIALIZ, $this);
        return $this;
    }

    protected function start()
    {
        self::$context->session->start();
        Event\Dispatcher::dispatch(Application::WEB_ON_START, $this);
        return $this;
    }

    protected function route()
    {
        Event\Dispatcher::dispatch(Application::WEB_PRE_ROUTE, $this);
        self::$context->router->route();
        Event\Dispatcher::dispatch(Application::WEB_POST_ROUTE, $this);
        return $this;
    }

    protected function handle()
    {
        Event\Dispatcher::dispatch(Application::WEB_PRE_HANDLER, $this);
        self::$context->result = self::$context->handler->handle();
        Event\Dispatcher::dispatch(Application::WEB_POST_HANDLER, $this);
        return $this;
    }

    protected function render()
    {
        Event\Dispatcher::dispatch(Application::WEB_PRE_RENDER, $this);
        self::$context->renderer->render();
        Event\Dispatcher::dispatch(Application::WEB_POST_RENDER, $this);
        return $this;
    }

    protected function finish()
    {
        self::$context->response->flush();
        Event\Dispatcher::dispatch(Application::WEB_ON_END, $this);
        exit();
    }

    public function quit()
    {
        $this->finish();
    }

    static public $settings = array();
    static public $context = null;
    private static $_instance = NULL;
    static public function singleton()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    static public function run()
    {
        self::$context = new Context();
        $cls = Configuration::$requestClass;
        self::$context->request = new $cls();
        $cls = Configuration::$responseClass;
        self::$context->response = new $cls();
        $cls = Configuration::$sessionClass;
        self::$context->session = new $cls();
        $cls = Configuration::$routerClass;
        self::$context->router = new $cls();
        $cls = Configuration::$handlerClass;
        self::$context->handler = new $cls();
        $cls = Configuration::$rendererClass;
        self::$context->renderer = new $cls();

        self::singleton()->initialize()->start()->route()->handle()->render()->finish();
    }

}
