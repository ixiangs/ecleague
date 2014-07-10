<?php
namespace Toy\Web;

use Toy\Event;

class Application
{

    const WEB_ON_INITIALIZE = 'webOnInitialize';
    const WEB_ON_START = 'webOnStart';
    const WEB_PRE_FILTRATE = 'webPreFiltrate';
    const WEB_POST_FILTRATE = 'webPostFiltrate';
    const WEB_PRE_ROUTE = 'webPreRoute';
    const WEB_POST_ROUTE = 'webPostRoute';
    const WEB_PRE_HANDLER = 'webPreHandler';
    const WEB_POST_HANDLER = 'webPostHandler';
    const WEB_PRE_RENDER = 'webPreRender';
    const WEB_POST_RENDER = 'webPostRender';
    const WEB_ON_END = 'webOnEnd';

    protected function __construct()
    {
    }

    protected function initialize()
    {
        $cls = Configuration::$initializerClass;
        $initer = new $cls();
        $initer->initialize();
        Event::dispatch(Application::WEB_ON_INITIALIZE, $this);
        return $this;
    }

    protected function start()
    {
        self::$context->session->start();
        Event::dispatch(Application::WEB_ON_START, $this);
        return $this;
    }

    protected function filtrate()
    {
        Event::dispatch(Application::WEB_PRE_FILTRATE, $this);
        self::$context->filter->filtrate();
        Event::dispatch(Application::WEB_POST_FILTRATE, $this);
        return $this;
    }

    protected function route()
    {
        Event::dispatch(Application::WEB_PRE_ROUTE, $this);
        self::$context->router->route();
        Event::dispatch(Application::WEB_POST_ROUTE, $this);
        return $this;
    }

    protected function handle()
    {
        Event::dispatch(Application::WEB_PRE_HANDLER, $this);
        self::$context->result = self::$context->handler->handle();
        Event::dispatch(Application::WEB_POST_HANDLER, $this);
        return $this;
    }

    protected function render()
    {
        Event::dispatch(Application::WEB_PRE_RENDER, $this);
        self::$context->renderer->render();
        Event::dispatch(Application::WEB_POST_RENDER, $this);
        return $this;
    }

    protected function finish()
    {
        self::$context->response->flush();
        Event::dispatch(Application::WEB_ON_END, $this);
        exit();
    }

    public function quit()
    {
        $this->finish();
    }

    static public $components = array();
    static public $context = null;
    private static $_instance = NULL;

    static function getRequestComponent()
    {
        foreach(self::$components as $com){
            if($com->getCode() == self::$context->router->component){
                return $com;
            }
        }
        return null;
    }

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

        $cls = Configuration::$filterClass;
        self::$context->filter = new $cls();

        $cls = Configuration::$routerClass;
        self::$context->router = new $cls();

        $cls = Configuration::$handlerClass;
        self::$context->handler = new $cls();

        $cls = Configuration::$localizeClass;
        self::$context->localize = new $cls();

        $cls = Configuration::$rendererClass;
        self::$context->renderer = new $cls();

        self::singleton()->initialize()->start()->filtrate()->route()->handle()->render()->finish();
    }

}
