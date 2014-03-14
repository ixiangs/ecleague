<?php
namespace Toys\Framework;

use Toys\Framework\Action;
use Toys\Util\ArrayUtil;

abstract class Controller {

	protected $context = null;
	protected $request = null;
	protected $response = null;
	protected $session = null;
	protected $router = null;
	protected $dispatcher = null;
	
	public function __construct() {
	}
	
	public function __get($name) {
		if ($this -> context -> hasItem($name)) {
			return $this -> context -> getItem($name);
		}
		return null;
	}	

	public function initialize($ctx) {
		$this->context = $ctx;
		$this->request = $ctx->getRequest();
		$this->response = $ctx->getResponse();
		$this->session = $ctx->getSession();
		$this->router = $ctx->getRouter();
		$this->dispatcher = $ctx->getDispatcher();
	}

	// public function setContext($value) {
		// $this -> _context = $value;
		// return $this;
	// }

	// public function getContext() {
		// return $this -> _context;
	// }
// 
	// public function getRequest() {
		// return $this -> _context -> getRequest();
	// }
// 
	// public function getResponse() {
		// return $this -> _context -> getResponse();
	// }
// 
	// public function getSession() {
		// return $this -> _context -> getSession();
	// }
// 
	// public function getRouter() {
		// return $this -> _context -> getRouter();
	// }
// 
	// public function getDispatcher() {
		// return $this -> _context -> getLanguage();
	// }
// 
	// public function getLocalize() {
		// return $this -> _context -> getLocalize();
	// }
// 	
	// public function getLanguage(){
		// return $this->_context->getLanguage();
	// }
// 	
	// public function getHistory() {
		// return $this -> _context -> getHistory();
	// }	

	public function execute($action) {
		$m = ucfirst(strtolower(ArrayUtil::get($_SERVER, 'REQUEST_METHOD', 'get')));
		$ajax = $this -> request -> isAjax();
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
				foreach($this->request->getAllParameters() as $n=>$v){
					foreach($params as $p){
						if($p->getName() == $n){
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