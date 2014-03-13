<?php
namespace Toys\Orm;

class ArrayProperty extends PropertyBase {

	private $_separator = ',';

	public function getSeparator() {
		return $this -> _separator;
	}

	public function setSeparator() {
		return $this -> _separator;
	}

	public function toDbValue($value) {
		if (empty($value)) {
			return null;
		}

		return implode($this -> _separator, $value);
	}

	public function fromDbValue($value) {
		if (empty($value)) {
			return array();
		}

		return explode($this -> _separator, $value);
	}

	public function validate($value) {
		if (empty($value)) {
			return $this -> getNullable();
		} else {
			return is_array($value);
		}
	}

}
