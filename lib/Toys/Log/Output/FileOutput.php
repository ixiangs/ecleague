<?php
namespace Toys\Log\Output;

class FileOutput implements \Toys\Log\IOutput {

	private $_settings = array();

	public function __construct($settings) {
		$this -> _settings = $settings;
	}

	public function write($content) {
		$filename = $this -> _settings['folder'] . DIRECTORY_SEPARATOR . date('Y-m-d');
		file_put_contents($filename, $content, FILE_APPEND);
	}

}
