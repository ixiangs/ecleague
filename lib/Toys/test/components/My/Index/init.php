<?php
use Toys\Event;
use Toys\Framework\Application;

Event\Configuration::addListener(Application::APPLICATION_ON_INITIALIZ, function($app){
	
});
