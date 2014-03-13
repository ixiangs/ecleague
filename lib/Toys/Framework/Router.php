<?php
namespace Toys\Framework;

use Toys\Util\StringUtil, Toys\Util\ArrayUtil;

class Router {

	private $_objective = NULL;

	public function route($url = null) {
		if(empty($url)){
			$url = $_SERVER['REQUEST_URI'];
		}
		$this -> _objective = $this -> parseUrl($url);
		return $this -> _objective;
	}

	public function buildUrl($url = "", $params = NULL) {
		list($len, $domain, $component, $controller, $action) = array(0, null, null, null, null);
		$ctx = Application::singleton()->getContext();
		if (!empty($url)) {
			$arr = explode('/', $url);
			$len = count($arr);
		}
		switch($len) {
			case 0 :
				$domain = $ctx -> getDomain();
				$component = StringUtil::PascalCasingToDash($this -> _objective -> getComponent());
				$controller = StringUtil::PascalCasingToDash(str_replace('Controller', '', $this -> _objective -> getController()));
				$action = $this -> _objective -> getAction();
				break;
			case 1 :
				$domain = $ctx -> getDomain();
				$component = StringUtil::PascalCasingToDash($this -> _objective -> getComponent());
				$controller = StringUtil::PascalCasingToDash(str_replace('Controller', '', $this -> _objective -> getController()));
				$action = $arr[0];
				break;
			case 2 :
				$domain = $ctx -> getDomain();
				$component = StringUtil::PascalCasingToDash($this -> _objective -> getComponent());
				$controller = $arr[0];
				$action = $arr[1];
				break;
			case 3 :
				$domain = $ctx -> getDomain();
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
		switch(Configuration::$urlFormat) {
			case Configuration::URL_FORMAT_NAME_PARAMETER :
				$url = $domain -> getIndexUrl();
				$url .= $component;
				$url .= '/' . $controller;
				$url .= '/' . $action;
				if (is_array($params)) {
					$url .= '?' . http_build_query($params);
				}
				return $url;
			case Configuration::URL_FORAMT_QUERY_STRING :
				$args = array();
				if (!$domain -> getDefault()) {
					$args['domain'] = $domain -> getName();
				}
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

	public function parseUrl($url) {
		$objective = new Objective();
		$defaultDomain = Configuration::$defaultDomain;
		//判断是否网站首页，如果是直接返回首页Action
		if ($url == $defaultDomain -> getIndexUrl()) {
			Application::singleton()->getContext()->setDomain($defaultDomain);
			$arr = explode('/', $defaultDomain->getIndexHandler());
			return $objective 
							-> setComponent($arr[0])
							-> setController($arr[1])
							-> setAction($arr[2]);
		}

		//如果使用URL参数专递
		$parts = $this -> parseQuery($url);
		// if (array_key_exists('query', $parts)) {
			// $query = $parts['query'];
			// if (ArrayUtil::hasAllKeys($query, array('domain', 'component', 'controller', 'action'))) {
				// return $objective 
					// -> setDomain(Configuration::$domains[$query['domain']]) 
					// -> setComponent(Configuration::$components[$query['component']])
					// -> setController($query['controller']) 
					// -> setAction($query['action']);
			// } elseif (ArrayUtil::hasAllKeys($query, array('component', 'controller', 'action'))) {
				// return $objective 
					// -> setDomain($defaultDomain) 
					// -> setComponent(Configuration::$components[$query['component']]) 
					// -> setController($query['controller']) 
					// -> setAction($query['action']);
			// }
		// }

		$url = $parts['path'];
		if ($url[strlen($url) - 1] != '/') {
			$url = $url . '/';
		}

		//匹配每个Domain的首页
		foreach (Configuration::$domains as $name => $v) {
			if ($v -> getIndexUrl() == $url) {
				Application::singleton()->getContext()->setDomain($v);
				$arr = explode('/', $v->getIndexHandler());
				return $objective 
								-> setComponent($arr[0])
								-> setController($arr[1])
								-> setAction($arr[2]);				
			}
		}
		
		//匹配每个Domain的开头
		foreach (Configuration::$domains as $v) {
			if (!$v -> getDefault() && StringUtil::startsWith($url, $v -> getIndexUrl())) {
				$arr = explode('/', trim($parts['path'], '/'));
				Application::singleton()->getContext()->setDomain($v);
				return $objective 
								-> setComponent($arr[1])
								-> setController($arr[2])
								-> setAction($arr[3]);
			}
		}

		//直接使用默认Domain
		Application::singleton()->getContext()->setDomain($defaultDomain);
		$arr = explode('/', $parts['path']);
		return $objective 
						-> setComponent($arr[1])
						-> setController($arr[2])
						-> setAction($arr[3]);
	}

	private function parseQuery($url) {
		$result = parse_url($url);
		if (array_key_exists('query', $result)) {
			parse_str($result['query'], $arr);
			$result['query'] = $arr;
		}
		return $result;
	}
}
