<?php
namespace Localization;
use Toy\Util\StringUtil;

class Listener{
	
	public static function applicationOnStart($app, $argument){
		$lang = $app->getContext()->getRequest()->getBrowserLanguage();
		Dictionary::singleton()->initialize($lang);
		Localize::singleton()->initialize($lang);
		
		$app->getContext()->setItem('languages', Dictionary::singleton());
		$app->getContext()->setItem('localize', Localize::singleton());
	}
	
}
