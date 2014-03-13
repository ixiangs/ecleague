<?php
namespace Toys\Framework;

use Toys\Localization\Dictionary;
use Toys\Localization\Localize;

class Output {
	
	// private function __construct(){}

	public function render() {
		$context = Application::singleton() -> getContext();
		$result = $context -> getObjective() -> getResult();
		$response = $context -> getResponse();
		switch($result->getType()) {
			case 'template' :
				$data = $result -> getData();
				$tmpl = new Template($data);
				$response -> write($tmpl -> render($result->getPath()));
				break;
			case 'content' :
				$response -> write($result -> getContent());
				break;
			case 'redirect' :
				$response -> redirect($result -> getUrl());
				break;
			case 'referer' :
				$to = array_key_exists('HTTP_REFERER', $_SERVER) ? $_SERVER['HTTP_REFERER'] : $result -> getUrl();
				$response -> redirect($to);
				break;
			case 'callback' :
				call_user_func_array($result -> getCallback(), $result -> getArguments());
				break;
			case 'json' :
				$data = $result -> getData();
				$response -> setHeader('content-type:application/json; charst=utf-8')
					-> write(json_encode($data));
				break;
			case 'download':
				$content = $result->getContent();
			  header('Content-Description: File Transfer');
			  header("Content-Type: application/octet-stream");
			  header('Content-Transfer-Encoding: binary');
			  header("Accept-Ranges:bytes");
			  header('Content-Disposition: attachment; filename='.$result->getFileName());
			  header("Accept-Length:".strlen($content));
			  header('Expires: 0');
			  header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			  header('Pragma: public');
				$response->write($content);
				break;
			case 'end' :
				Application::singleton() -> end();
				break;
		}
	}

	// private static $_instance = NULL;
	// public static function singleton() {
		// if (is_null(self::$_instance)) {
			// self::$_instance = new self();
		// }
		// return self::$_instance;
	// }

}
