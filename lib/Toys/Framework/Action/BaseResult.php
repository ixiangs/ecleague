<?php
namespace Toys\Framework\Action;

use Toys\Application;

abstract class BaseResult{
	
	private $_context = NULL;
	
	public function __construct(){
		$this->_context = Application::singleton();
	}
	
	public function getContext(){
		return $this->_context;
	}	
	
	public abstract function getType();
}
