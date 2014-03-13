<?php
namespace Toys\Framework;

class Component{
	
	private $_name = '';
	private $_package = '';
	private $_listeners = array();
	private $_events = array();
	
	public function __construct($name, $package, $listeners, $events){
		$this->_name = $name;
		$this->_package = $package;
		$this->_listeners = $listeners;
		$this->_events = $events;
	}
	
	public function getName(){
		return $this->_name;
	}
	
	public function getPackage(){
		return $this->_package;
	}
	
	public function getListeners(){
		return $this->_listeners;
	}	
	
	public function getEvents(){
		return $this->_events;
	}	
}
