<?php
namespace History;

class Recorder{
	
	private $_items = null;
	private $_session = null;
	
	private function __construct(){}
	
	public function add($url){
		if(count($this->_items) == 0 || (count($this->_items) > 0 && $this->_items[0] != $url)){
			$this->_items = array_merge(array($url), $this->_items);
		}
		if(count($this->_items) > 10){
			$this->_items = array_slice($this->_items, 0, 10);
		}
		$this->_session->set('__history', serialize($this->_items));
	}
	
	public function find($prefix, $default = null){
		$len = strlen($prefix);
		foreach($this->_items as $item){
			if(substr($item, 0, $len) == $prefix){
				return $item;
			}
		}
		return $default;
	}
	
	public function getLast(){
		return $this->_items[0];
	}
	
	public function load(){
		if(is_null($this->_session)){
			$this->_session = \Toy\Web\Application::singleton()->getContext()->getSession();
		}
		if(is_null($this->_items)){
			$h = $this->_session->pop('__history');
			$this->_items = $h? unserialize($h): array();
		}			
		return $this;
	}
	
	private static $_instance = NULL;
	public static function singleton(){
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;		
	}
}
