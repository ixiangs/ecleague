<?php
namespace Toy\Log;

class FileAppender extends BaseAppender {

	private $_directory = null;

	public function __construct() {
		$this ->_directory = Configuration::$settings['directory'];
	}

	public function append($content) {
		$filename = $this -> _directory . DIRECTORY_SEPARATOR . date('Y-m-d');
		file_put_contents($filename, $content, FILE_APPEND);
	}

}
