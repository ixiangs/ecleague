<?php
namespace Toy\View\Html;

use Toy\Web\Template;

class Listener{
	
	static public function applicationOnStart($app, $argument){
        Template::addHelper('html', Helper::singleton());
	}
	
}
