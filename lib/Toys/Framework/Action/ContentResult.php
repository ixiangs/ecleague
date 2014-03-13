<?php
namespace Toys\Framework\Action;

use Toys\Framework;

class ContentResult extends BaseResult{
    
    private $_content = "";

    public function __construct($content){
        $this->_content = $content;
    }
    
    public function getContent(){
        return $this->_content;
    }
    
    public function setContent($value){
        $this->_content = $value;
        return $this;
    }
	
    public function getType(){
        return 'content';
    }
}
