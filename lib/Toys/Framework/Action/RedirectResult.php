<?php
namespace Toys\Framework\Action;

use Toys\Framework;

class RedirectResult extends BaseResult{
	
	private $_url = NULL;

	public function __construct($url){
		$this->_url = $url;
	}
	
	public function getUrl(){
		return $this->_url;
	}
	
	public function setUrl($value){
		$this->_url = $value;
		return $this;
	}
	
	public function getType(){
		return 'redirect';
	}
}
