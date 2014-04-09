<?php
namespace Toy\Web;

use Toy\Util\StringUtil, Toy\Util\ArrayUtil;

class Router
{

    public $component = null;
    public $controller = null;
    public $action = null;

    public function route($url = null)
    {
        if (empty($url)) {
            $url = $_SERVER['REQUEST_URI'];
        }
        $arr = $this->parseUrl($url);
        $this->component = $arr['component'];
        $this->controller = $arr['controller'];
        $this->action = $arr['action'];
    }

    public function buildUrl($url = "", $params = NULL)
    {
        list($len, $domain, $component, $controller, $action) = array(0, null, null, null, null);
        if (!empty($url)) {
            $arr = explode('/', $url);
            $len = count($arr);
        }
        switch ($len) {
            case 0 :
                $component = $this->component;
                $controller = $this->controller;
                $action = $this->action;
                break;
            case 1 :
                $component = $this->component;
                $controller = $this->controller;
                $action = $arr[0];
                break;
            case 2 :
                $component = $this->component;
                $controller = $arr[0];
                $action = $arr[1];
                break;
            case 3 :
                $component = $arr[0];
                $controller = $arr[1];
                $action = $arr[2];
                break;
        }
        if (Configuration::$seoUrl) {
            $url = $domain->getStartUrl();
            $url .= $component;
            $url .= '/' . $controller;
            $url .= '/' . $action;
            if (is_array($params)) {
                $url .= '?' . http_build_query($params);
            }
            return $url;
        } else {
            $args = array();
            $args['component'] = $component;
            $args['controller'] = $controller;
            $args['action'] = $action;
            $url = http_build_query($args);
            if (is_array($params)) {
                $url .= '&' . http_build_query($params);
            }
            return '/?' . $url;
        }
    }

    public function parseUrl($url)
    {

//		//判断是否网站首页，如果是直接返回首页Action
        if ($url ==  Configuration::$indexUrl) {
            return array('component' => 'index', 'controller' => 'index', 'action' => 'index');
        }

        //如果使用URL参数专递
        $parts = $this->parseQuery($url);
        if (array_key_exists('query', $parts)) {
            $query = $parts['query'];
            if (ArrayUtil::hasAllKeys($query, array('component', 'controller', 'action'))) {
                return array(
                    'component' => $query['component'],
                    'controller' => $query['controller'],
                    'action' => $query['action']);
            }
        }

        $arr = explode('/', $url);
        return array('component' => $arr[0], 'controller' => $arr[1], 'action' => $arr[2]);
    }

    private function parseQuery($url)
    {
        $result = parse_url($url);
        if (array_key_exists('query', $result)) {
            parse_str($result['query'], $arr);
            $result['query'] = $arr;
        }
        return $result;
    }
}
