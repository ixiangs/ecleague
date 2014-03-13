<?php
namespace Toys\Unit;

abstract class TestCase {

	protected function fail($msg) {
		throw new AssertException($msg);
	}

	protected function assertEqual($a, $b, $content = null) {
		if ($a != $b) {
			throw new AssertException("$a != $b");
		}
	}

	protected function assertNotEqual($a, $b, $content = null) {
		if ($a == $b) {
			throw new AssertException("$a == $b");
		}
	}

	protected function assertEmpty($a, $content = null) {
		if (!empty($a)) {
			throw new AssertException("$a is not empty");
		}
	}

	protected function assertNotEmpty($a, $content = null) {
		if (empty($a)) {
			throw new AssertException("$a is empty");
		}
	}

	protected function assertNull($v, $content = null) {
		if (!is_null($v)) {
			throw new AssertException("$v");
		}
	}

	protected function assertNotNull($v, $content = null) {
		if (is_null($v)) {
			throw new AssertException("$v");
		}
	}

	protected function assertTrue($v, $content = null) {
		if ($v !== TRUE) {
			throw new AssertException("is not ture");
		}
	}

	protected function assertFalse($v, $content = null) {
		if ($v !== false) {
			throw new AssertException("is not false");
		}
	}

}
