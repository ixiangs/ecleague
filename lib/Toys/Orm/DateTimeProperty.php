<?php
namespace Toys\Orm;

class DateTimeProperty extends PropertyBase {

	private $_format = 'Y-m-d H:i:s';
	
	public function __construct(){
		$this->setDefaultValue('now');
	}

	public function getFormat() {
		return $this -> _format;
	}

	public function setFormat($value) {
		$this -> _format = $value;
		return $this;
	}

	public function toDbValue($value) {
		if (empty($value) && $this->getDefaultValue() == 'now') {
			return date($this -> _format);
		}
		return parent::toDbValue($value);
	}

	public function validate($value) {
		if (empty($value)) {
			return $this -> getNullable();
		} else {
			return TRUE;
		}
	}

}
