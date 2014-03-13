<?php
namespace Toys\Framework\Action;

class CallbackResult extends BaseResult{
	
	private $_arguments = NULL;
	private $_callback = NULL;

	public function __construct($callback, $arguments = null){
		$this->_callback = $callback;
		$this->_arguments = $arguments;
	}
	
	public function getCallback(){
		return $this->_callback;
	}
	
	public function setCallback($value){
		$this->_callback = $value;
		return $this;
	}
	
	public function getArguments(){
		return $this->_arguments;
	}
	
	public function setArguments($value){
		$this->_arguments = $value;
		return $this;
	}

	public function getType(){
		return 'callback';
	}
}
