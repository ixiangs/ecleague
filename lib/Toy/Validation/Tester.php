<?php
namespace Toy\Validation;

class Tester {

    private static $_emailRegex = '/^([^@\s]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/';

	static public function testNotEmpty($value) {
		if (is_null($value)) {
			return false;
		}
		if (is_array($value)) {
			return count($value) > 0;
		}
		if (strlen($value) == 0) {
			return false;
		}
		return TRUE;
	}

	static public function testInteger($value) {
		if (is_int($value)) {
			return TRUE;
		}

		return preg_match("/^[1-9]\d*|[0-9]$/", $value) > 0;
	}

	static public function testDigit($value) {
		return preg_match("/^\d+$/", $value) > 0;
	}

	static public function testNumeric($value) {
		return is_numeric($value);
	}

	static public function testEmail($value) {
        return preg_match(self::$_emailRegex, $value) > 0;
	}

	static public function testDateTime($value) {
		if (is_string($value)) {
			$date = date_parse($value);
			return $date['error_count'] == 0;
		}

		return false;
	}

	static public function testMaxLength($value, $length) {
		return strlen($value) <= $length;
	}

	static public function testMinLength($value, $length) {
		return strlen($value) >= $length;
	}

	static public function testRangeLength($value, $min, $max) {
		return strlen($value) <= $max && strlen($value) >= $min;
	}

	static public function testMaxValue($value, $max) {
		return $value <= $max;
	}

	static public function testMinValue($value, $min) {
		return $value >= $min;
	}

	static public function testRangeValue($value, $min, $max) {
		if (is_numeric($min) && $value < $min) {
			return false;
		}

		if (is_numeric($value) && $value > $max) {
			return false;
		}

		return TRUE;
	}

	static public function testAlpha($value) {
		return preg_match("/[a-zA-Z]+/", $value) > 0;
	}

	static public function testAlphanum($value) {
		return preg_match("/[a-zA-Z0-9]+/", $value) > 0;
	}

	static public function testRegex($value, $pattern) {
		return preg_match($this -> getPattern(), $value) > 0;
	}

}
