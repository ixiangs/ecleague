<?php
namespace Toys\Framework\Action;

use Toys\Framework\Router, Toys\Framework\Application;

class TemplateResult extends BaseResult {

	private $_data = NULL;
	private $_path = NULL;

	public function __construct($data = null, $path = null) {
		$this -> _path = $path;
		$this -> _data = $data;
	}

	public function getData() {
		return $this -> _data;
	}

	public function setData($value) {
		$this -> _data = $value;
		return $this;
	}

	public function getPath() {
		return $this -> _path;
	}

	public function setPath($value) {
		$this -> _path = $value;
		return $this;
	}

	public function getType() {
		return 'template';
	}

}
