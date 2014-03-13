<?php
namespace Html;
use Toys\Util\StringUtil;

class Listener{
	
	public static function applicationOnStart($app, $argument){
		$app->getContext()->setItem('html', Helper::singleton());
	}
	
}
