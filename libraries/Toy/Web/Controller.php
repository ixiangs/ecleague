<?php
namespace Toy\Web;

use Toy\Web\Action;
use Toy\Util\ArrayUtil;

abstract class Controller
{

    protected $context = null;
    protected $request = null;
    protected $response = null;
    protected $session = null;
    protected $router = null;

    public function __construct()
    {
    }

    public function initialize($ctx)
    {
        $this->context = $ctx;
        $this->request = $ctx->request;
        $this->response = $ctx->response;
        $this->session = $ctx->session;
        $this->router = $ctx->router;
    }

    public function ready()
    {

    }

    public function finish()
    {

    }

    public function execute($action)
    {
        $m = ucfirst(strtolower(ArrayUtil::get($_SERVER, 'REQUEST_METHOD', 'get')));
        $ajax = $this->request->isAjax();
        $lcAction = lcfirst(str_replace(' ', '', ucwords(str_replace('-', ' ', $action))));
        $methods = array();
        if ($ajax) {
            $methods[] = $lcAction . 'AjaxAction';
            $methods[] = $lcAction . 'Ajax' . $m . 'Action';
        }
        $methods[] = $lcAction . $m . 'Action';
        $methods[] = $lcAction . 'Action';
        foreach ($methods as $method) {
            if (method_exists($this, $method)) {
                $rf = new \ReflectionClass($this);
                $params = $rf->getMethod($method)->getParameters();
                $arguments = array();
                foreach ($this->request->getAllParameters() as $n => $v) {
                    foreach ($params as $p) {
                        if ($p->getName() == $n) {
                            $arguments[] = $v;
                            break;
                        }
                    }
                }
                return call_user_func_array(array($this, $method), $arguments);
            }
        }

        throw new Exception('Not found [' . (implode(',', $methods)) . '] in controller [' . get_class($this) . ']');
    }

}
