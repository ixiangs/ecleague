<?php
namespace Toys\Framework;

class Context {

	protected $request = null;
	protected $response = null;
	protected $session = null;
	protected $router = null;
	protected $output = null;
	protected $objective = null;
	protected $domain = null;
	protected $items = array();

	public function __construct() {}

	public function getItem($name, $default = NULL) {
		if(array_key_exists($name, $this -> items)){
			return $this -> items[$name];	
		}
		return $default;
	}

	public function setItem($name, $value) {
		$this -> items[$name] = $value;
		return $this;
	}

	public function hasItem($name) {
		return array_key_exists($name, $this -> items);
	}

	public function getRouter() {
		return $this -> router;
	}

	public function setRouter($value) {
		$this -> router = $value;
		return $this;
	}

	public function getDispatcher() {
		return $this -> dispatcher;
	}

	public function setDispatcher($value) {
		$this -> dispatcher = $value;
		return $this;
	}

	public function getObjective() {
		return $this -> objective;
	}

	public function setObjective($value) {
		$this -> objective = $value;
		return $this;
	}
	
	public function getDomain() {
		return $this -> domain;
	}
	
	public function setDomain($value){
		$this->domain = $value;
		return $this;
	}	

	public function getRequest() {
		return $this -> request;
	}

	public function setRequest($value) {
		$this -> request = $value;
		return $this;
	}

	public function getResponse() {
		return $this -> response;
	}

	public function setResponse($value) {
		$this -> response = $value;
		return $this;
	}

	public function getSession() {
		return $this -> session;
	}

	public function setSession($value) {
		$this -> session = $value;
		return $this;
	}
	
	public function getOutput() {
		return $this -> output;
	}

	public function setOutput($value) {
		$this -> output = $value;
		return $this;
	}	

	// public function getLanguage() {
		// return $this -> language;
	// }
// 
	// public function setLanguage($value) {
		// $this -> language = $value;
		// return $this;
	// }

	// public function getLocalize() {
		// return $this -> localize;
	// }
// 
	// public function setLocalize($value) {
		// $this -> localize = $value;
		// return $this;
	// }
}
