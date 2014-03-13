<?php
namespace Toys\Framework\Action;

use Toys\Framework;

class JsonResult extends BaseResult{
	
	private $_data = NULL;

	public function __construct($data){
		$this->_data = $data;
	}
	
	public function getData(){
		return $this->_data;
	}
	
	public function setData($value){
		$this->_data = $value;
		return $this;
	}	
	
	public function getType(){
		return 'json';
	}
}
