<?php
namespace Toy\Web;

use Toy\Util\ArrayUtil;
use Toy\Util\StringUtil;

class Router
{

    private static $_SESSION_KEY = '__route_histories';

    public $domain = null;
    public $component = null;
    public $controller = null;
    public $action = null;
    private $_histories = null;
    private $_session = null;

    public function __construct()
    {
        $this->_session = Application::$context->session;
    }

    public function getHistories()
    {
        return $this->_histories;
    }

    public function findHistory($action, $defaultArgs = null)
    {
        $baction = $this->buildAction($action);
        foreach ($this->_histories as $history) {
            if ($history['action'] == $baction) {
                return $history['url'];
            }
        }
        return $this->buildUrl($action, $defaultArgs);
    }

    public function route($url = null)
    {
        $url = empty($url) ? $_SERVER['REQUEST_URI'] : $url;
        $arr = $this->parseUrl($url);
        $this->domain = $arr['domain'];
        $this->component = $arr['component'];
        $this->controller = $arr['controller'];
        $this->action = $arr['action'];

        if (is_null($this->_histories)) {
            $this->_histories = $this->_session->get(self::$_SESSION_KEY, array());
        }

        array_unshift($this->_histories, array(
            'action' => sprintf('%s%s/%s/%s', $this->domain->getStartUrl(), $this->component, $this->controller, $this->action),
            'url' => $url));
        if (count($this->_histories) >= 10) {
            unset($this->_histories[10]);
        }
        $this->_session->set(self::$_SESSION_KEY, $this->_histories);
        return $this;
    }

    public function buildAction($url = "")
    {
        $parts = parse_url($url);
        list($len, $domain, $component, $controller, $action) = array(0, null, null, null, null);
        if (!empty($url)) {
            $arr = explode('/', $parts['path']);
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
        $parts['path'] = $domain->getStartUrl() . $component . '/' . $controller . '/' . $action;
        $result = $parts['path'];
        if(array_key_exists('query', $parts)){
            $result .= '?'.$parts['query'];
        }
        if(array_key_exists('fragment', $parts)){
            $result .= '#'.$parts['fragment'];
        }
        return $result;
    }

    public function buildUrl($url = "", $params = NULL)
    {
        $url = $this->buildAction($url);
        if (is_array($params)) {
            $arr = array();
            foreach ($params as $k => $v) {
                if (!empty($v)) {
                    $arr[$k] = $v;
                }
            }
            if (count($arr) > 0) {
                if(strpos('?', $url) === false){
                    $url .= '?' . http_build_query($arr);
                }else{
                    $url .= '&' . http_build_query($arr);
                }
            }
        } elseif (is_string($params) && $params == '*') {
            $query = Application::$context->request->getAllQuery();
            if (is_array($query) && count($query) > 0) {
                if(strpos('?', $url) === false){
                    $url .= '?' . http_build_query($query);
                }else{
                    $url .= '&' . http_build_query($query);
                }
            }
        }
        return $url;
    }

    public function parseUrl($url)
    {
        $defaultDomain = Configuration::$domains[Configuration::$defaultDomain];

//		//判断是否网站首页，如果是直接返回首页Action
        if ($url == $defaultDomain->getStartUrl()) {
            $parts = explode('/', $defaultDomain->getIndexUrl());
            return array('domain' => $defaultDomain,
                'component' => $parts[0],
                'controller' => $parts[2],
                'action' => $parts[3]);
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
                if (empty($suburl)) {
                    $parts = explode('/', $v->getIndexUrl());
                    return array('domain' => $v,
                        'component' => $parts[0],
                        'controller' => $parts[1],
                        'action' => $parts[2]);
                } else {
                    $arr = explode('/', $suburl);
                    return array('domain' => $v,
                        'component' => $arr[0],
                        'controller' => $arr[1],
                        'action' => $arr[2]);
                }
            }
        }

        //直接使用默认Domain
        $suburl = stristr($url, $defaultDomain->getStartUrl());
        $arr = explode('/', $suburl);
        return array('domain' => $v,
            'component' => $arr[0],
            'controller' => $arr[1],
            'action' => $arr[2]);
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
