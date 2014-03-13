<?php
namespace History;
use Toys\Util\StringUtil;

class Listener{
	
	public static function applicationOnStart($app, $argument){
		Recorder::singleton()->load()->add($_SERVER['REQUEST_URI']);
		$app->getContext()->setItem('history', Recorder::singleton());
	}
	
}
