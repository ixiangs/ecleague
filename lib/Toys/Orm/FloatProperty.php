<?php
namespace Toys\Orm;
use Toys\Validation\Tester;

class FloatProperty extends PropertyBase {

	private $_minValue = null;
	private $_maxValue = null;

	public function getMinValue() {
		return $this -> _minValue;
	}

	public function getMaxValue() {
		return $this -> _maxValue;
	}

	public function setRangeValue($min, $max) {
		$this -> _minValue = $min;
		$this -> _maxValue = $max;
		return $this;
	}

	public function validate($value) {
		$empty = is_null($value);
		if ($empty) {
			return $this -> getNullable();
		} else {
			if(!Tester::testNumeric($value)){
				return false;
			}
			
			if (!is_null($this -> _minValue) && $value < $this -> _minValue) {
				return false;
			}

			if (!is_null($this -> _maxValue) && $value > $this -> _maxValue) {
				return false;
			}

			return TRUE;
		}
	}

}
