<?php
namespace Toys\Log;

class FileAppender extends BaseAppender {

	private $_filename = null;

	public function __construct($settings) {
		$this ->_filename = Toys\Log\Configuration::$settings['filename'];
	}

	public function append($content) {
//		$filename = $this -> _settings['folder'] . DIRECTORY_SEPARATOR . date('Y-m-d');
		file_put_contents($this ->_filename, $content, FILE_APPEND);
	}

}
