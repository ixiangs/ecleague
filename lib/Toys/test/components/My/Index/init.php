<?php
use Toys\Event;
use Toys\Web\Application;

Event\Configuration::addListener(Application::APPLICATION_ON_INITIALIZ, function($app){
	
});
