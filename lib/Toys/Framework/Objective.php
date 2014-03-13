<?php
namespace Toys\Framework;

class Objective {

	// private $_package = null;
	private $_component = null;
	private $_controller = null;
	private $_action = null;
	private $_parameters = null;
	private $_result = null;

	public function __construct() {}
	
	// public function setHandler($handler){
		// $arr = explode(':', $handler);
		// $this->_action = $arr[1];
		// $arr2 = explode('\\', $arr[0]);
		// $this->_package = $arr2[0];
		// $this->_component = $arr2[1];
		// $this->_controller = $arr2[3];
		// return $this;
	// }
	
	public function getAction() {
		return $this -> _action;
	}
	
	public function setAction($value){
		$this->_action = $value;
		return $this;
	}
	
	// public function getPackage(){
		// return $this->_package;
	// }
	
	public function getController() {
		return $this -> _controller;
	}	
	
	public function setController($value){
		$this->_controller = $value;
		return $this;
	}
	
	public function getComponent() {
		return $this -> _component;
	}		
	
	public function setComponent($value){
		$this->_component = $value;
		return $this;
	}

	// public function getDomain(){
		// return $this->_domain;
	// }

	public function getParameters() {
		return $this -> _parameters;
	}

	public function setParameters($value) {
		$this -> _parameters = $value;
		return $this;
	}	
	
	public function getResult(){
		return $this->_result;
	}
	
	public function setResult($value){
		$this->_result = $value;
	}
	
	// public function setController($value){
		// $this->_controller = $value;
		// return $this;
	// }		
}
