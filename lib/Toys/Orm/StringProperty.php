<?php
namespace Toys\Orm;

class StringProperty extends PropertyBase {

	private $_minLength = null;
	private $_maxLength = null;
	private $_regexp = null;
	
	public function getMinLength() {
		return $this -> _minLength;
	}

	public function getMaxLength() {
		return $this -> _maxLength;
	}
	
	public function getRegexp(){
		return $this->_regexp;
	}
		
	public function setRegexp($value){
		$this->_regexp = $value;
		return $this;
	}

	public function setRangeLength($min, $max) {
		$this -> _minLength = $min;
		$this -> _maxLength = $max;
		return $this;
	}

	public function validate($value) {
		$empty = is_null($value);
		if($empty){
			return $this->getNullable();
		}else{
			$len = strlen($value);
			if(!is_null($this->_minLength) && $len < $this->_minLength){
				return false;
			}
			
			if(!is_null($this->_maxLength) && $len > $this->_maxLength){
				return false;
			}
			
			if(!empty($this->_regexp)){
				return preg_match($this->_regexp, $value) > 0;
			}
			
			return TRUE;
		}
	}
}
