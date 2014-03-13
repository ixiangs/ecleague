<?php
namespace Toys\Framework\Action;

use Toys\Framework;

class DownloadResult extends BaseResult{
	
	private $_filename = null;
	private $_content = null;

	public function __construct($filename, $content){
		$this->_filename = $filename;
		$this->_content = $content;
	}
	
	public function setFilename($value){
		$this->_filename = $value;
		return $this;
	}
	
	public function getFilename(){
		return $this->_filename;
	}
	
	public function getContent(){
		return $this->_content;
	}
	
	public function setContent($value){
		$this->_content = $value;
		return $this;
	}
	
	public function getType(){
		return 'download';
	}	
}
