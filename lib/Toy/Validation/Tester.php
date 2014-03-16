<?php
namespace Toy\Validation;

class Tester {

	public static function testNotEmpty($value) {
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

	public static function testInteger($value) {
		if (is_int($value)) {
			return TRUE;
		}

		return preg_match("/^[1-9]\d*|[0-9]$/", $value) > 0;
	}

	public static function testDigit($value) {
		return preg_match("/^\d+$/", $value) > 0;
	}

	public static function testNumeric($value) {
		return is_numeric($value);
	}

	public static function testEmail($value) {
		return filter_var($value, FILTER_VALIDATE_EMAIL);
	}

	public static function testDateTime($value) {
		if (is_string($value)) {
			$date = date_parse($value);
			return $date['error_count'] == 0;
		}

		return false;
	}

	public static function testMaxLength($value, $length) {
		return strlen($value) <= $length;
	}

	public static function testMinLength($value, $length) {
		return strlen($value) >= $length;
	}

	public static function testRangeLength($value, $min, $max) {
		return strlen($value) <= $max && strlen($value) >= $min;
	}

	public static function testMaxValue($value, $max) {
		return $value <= $max;
	}

	public static function testMinValue($value, $min) {
		return $value >= $min;
	}

	public static function testRangeValue($value, $min, $max) {
		if (is_numeric($min) && $value < $min) {
			return false;
		}

		if (is_numeric($value) && $value > $max) {
			return false;
		}

		return TRUE;
	}

	public static function testAlpha($value) {
		return preg_match("/[a-zA-Z]+/", $value) > 0;
	}

	public static function testAlphanum($value) {
		return preg_match("/[a-zA-Z0-9]+/", $value) > 0;
	}

	public static function testRegex($value, $pattern) {
		return preg_match($this -> getPattern(), $value) > 0;
	}

}
