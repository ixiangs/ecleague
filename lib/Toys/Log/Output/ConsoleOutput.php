<?php
namespace Toys\Log\Output;

class ConsoleOutput implements \Toys\Log\IOutput{
	
	private $_settings = array();
	
	public function __construct($settings){
		$this->_settings = $settings;
	}
	
	public function write($content){
		print $content;
	}
}
