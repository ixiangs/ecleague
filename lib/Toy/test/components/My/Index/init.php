<?php
use Toy\Event;
use Toy\Web\Application;

Event\Configuration::addListener(Application::WEB_ON_INITIALIZE, function ($app) {

});
