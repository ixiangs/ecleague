<?php
namespace Html;
use Toy\Util\StringUtil;

class Listener{
	
	public static function applicationOnStart($app, $argument){
		$app->getContext()->setItem('html', Helper::singleton());
	}
	
}
