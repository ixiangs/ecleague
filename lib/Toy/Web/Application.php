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

    protected function __construct()
    {
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

    protected function initialize()
    {
        $cls = Configuration::$initializerClass;
        $initer = new $cls();
        $initer->initialize();
        Event\Dispatcher::dispatch(Application::APPLICATION_ON_INITIALIZ, $this);
        return $this;
    }

//    private function initializeComponents()
//    {
//        PathUtil::scanCurrent(Configuration::$configurationPath, function ($file, $info) {
//            $cont = FileUtil::readFile($file);
//            if (preg_match_all('/(<[@]\w+>)/i', $cont, $matches)) {
//                foreach ($matches[0] as $match) {
//                    $key = substr($match, 1, -1);
//                    if ($key[0] == '@') {
//                        $cont = str_replace($match, str_replace('\\', '\\\\', constant(substr($key, 1))), $cont);
//                    }
//                }
//            }
//            $conf = json_decode($cont, true);
//            switch (json_last_error()) {
//                case JSON_ERROR_NONE:
//                    self::$settings[$conf['name']] = $conf;
//                    break;
//                case JSON_ERROR_DEPTH:
//                    echo 'Maximum stack depth exceeded';
//                    break;
//                case JSON_ERROR_STATE_MISMATCH:
//                    echo 'Underflow or the modes mismatch';
//                    break;
//                case JSON_ERROR_CTRL_CHAR:
//                    echo 'Unexpected control character found';
//                    break;
//                case JSON_ERROR_SYNTAX:
//                    echo 'Syntax error, malformed JSON';
//                    break;
//                case JSON_ERROR_UTF8:
//                    echo 'Malformed UTF-8 characters, possibly incorrectly encoded';
//                    break;
//                default:
//                    echo ' - Unknown error';
//                    break;
//            }
//        });
//
//        foreach (self::$settings as $conf) {
//            if (array_key_exists('listeners', $conf)) {
//                foreach ($conf['listeners'] as $en => $eh) {
//                    Event\Configuration::addListener($en, $eh);
//                }
//            }
//        }
//    }

    protected function start()
    {
        self::$context->session->start();
        Event\Dispatcher::dispatch(Application::APPLICATION_ON_START, $this);
        return $this;
    }

    protected function route()
    {
        Event\Dispatcher::dispatch(Application::APPLICATION_PRE_ROUTE, $this);
        self::$context->router->route();
        Event\Dispatcher::dispatch(Application::APPLICATION_POST_ROUTE, $this);
        return $this;
    }

    protected function handle()
    {
        Event\Dispatcher::dispatch(Application::APPLICATION_PRE_HANDLER, $this);
        self::$context->result = self::$context->handler->handle();
        Event\Dispatcher::dispatch(Application::APPLICATION_POST_HANDLER, $this);
        return $this;
    }

    protected function render()
    {
        Event\Dispatcher::dispatch(Application::APPLICATION_PRE_RENDER, $this);
        self::$context->renderer->render();
        Event\Dispatcher::dispatch(Application::APPLICATION_POST_RENDER, $this);
        return $this;
    }

    protected function finish()
    {
        self::$context->response->flush();
        Event\Dispatcher::dispatch(Application::APPLICATION_ON_END, $this);
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
