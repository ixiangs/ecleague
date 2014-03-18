<?php
namespace Html;
use Toy\Util\StringUtil;

class Listener{
	
	static public function applicationOnStart($app, $argument){
		$app->getContext()->setItem('html', Helper::singleton());
	}
	
}
