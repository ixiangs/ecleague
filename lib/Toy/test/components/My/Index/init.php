<?php
use Toy\Event;
use Toy\Web\Application;

Event\Configuration::addListener(Application::APPLICATION_ON_INITIALIZ, function($app){
	
});
