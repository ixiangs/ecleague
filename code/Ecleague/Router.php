<?php
namespace Ecleague;

use Toy\Util\ArrayUtil;
use Toy\Util\StringUtil;

class Router
{

    public $domain = null;
    public $component = null;
    public $controller = null;
    public $action = null;

    public function route($url = null)
    {
        if (empty($url)) {
            $url = $_SERVER['REQUEST_URI'];
        }
        $arr = $this->parseUrl($url);
        $this->domain = $arr['domain'];
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
                $domain = $this->domain;
                $component = $this->component;
                $controller = $this->controller;
                $action = $this->action;
                break;
            case 1 :
                $domain = $this->domain;
                $component = $this->component;
                $controller = $this->controller;
                $action = $arr[0];
                break;
            case 2 :
                $domain = $this->domain;
                $component = $this->component;
                $controller = $arr[0];
                $action = $arr[1];
                break;
            case 3 :
                $domain = $this->domain;
                $component = $arr[0];
                $controller = $arr[1];
                $action = $arr[2];
                break;
            case 4 :
                $domain = Configuration::$domains[$arr[0]];
                $component = $arr[1];
                $controller = $arr[2];
                $action = $arr[3];
                break;
        }
//        if (Configuration::$seoUrl) {
        $url = $domain->getStartUrl();
        $url .= $component;
        $url .= '/' . $controller;
        $url .= '/' . $action;
        if (is_array($params)) {
            $url .= '?' . http_build_query($params);
        }
        return $url;
//        } else {
//            $args = array();
//            if (!$domain->getDefault()) {
//                $args['domain'] = $domain->getName();
//            }
//            $args['component'] = $component;
//            $args['controller'] = $controller;
//            $args['action'] = $action;
//            $url = http_build_query($args);
//            if (is_array($params)) {
//                $url .= '&' . http_build_query($params);
//            }
//            return '/?' . $url;
//        }
    }

    public function parseUrl($url)
    {
        $defaultDomain = ArrayUtil::find(Configuration::$domains, function ($item, $index) {
            return $item->getDefault();
        });

//		//判断是否网站首页，如果是直接返回首页Action
        if ($url == $defaultDomain->getStartUrl()) {
            return array('domain' => $defaultDomain, 'component' => 'index', 'controller' => 'index', 'action' => 'index');
        }

        //如果使用URL参数专递
        $parts = $this->parseQuery($url);
        if (array_key_exists('query', $parts)) {
            $query = $parts['query'];
            if (ArrayUtil::hasAllKeys($query, array('domain', 'component', 'controller', 'action'))) {
                return array(
                    'domain' => Configuration::$domains[$query['domain']],
                    'component' => $query['component'],
                    'controller' => $query['controller'],
                    'action' => $query['action']);
            } elseif (ArrayUtil::hasAllKeys($query, array('component', 'controller', 'action'))) {
                return array(
                    'domain' => $defaultDomain,
                    'component' => $query['component'],
                    'controller' => $query['controller'],
                    'action' => $query['action']);
            }
        }

        $url = $parts['path'];
        if ($url[strlen($url) - 1] != '/') {
            $url = $url . '/';
        }

        //匹配每个Domain的首页
        foreach (Configuration::$domains as $v) {
            if (!$v->getDefault() && StringUtil::startsWith($url, $v->getStartUrl())) {
                $suburl = substr($url, strlen($v->getStartUrl()));
                if (strlen($suburl) == 0) {
                    return array('domain' => $v, 'component' => 'index', 'controller' => 'index', 'action' => 'index');
                } else {
                    $arr = explode('/', $suburl);
                    return array('domain' => $v, 'component' => $arr[0], 'controller' => $arr[1], 'action' => $arr[2]);
                }
            }
        }

        //直接使用默认Domain
        $suburl = stristr($url, $defaultDomain->getStartUrl());
        $arr = explode('/', $suburl);
        return array('domain' => $defaultDomain, 'component' => $arr[0], 'controller' => $arr[1], 'action' => $arr[2]);
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
