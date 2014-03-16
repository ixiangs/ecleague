<?php
\Toy\Event\Configuration::addListener(
	\Toy\Web\Application::APPLICATION_ON_START,
	array('Auth\Listener', 'applicationOnStart'));
\Toy\Event\Configuration::addListener(
	\Toy\Web\Application::APPLICATION_POST_ROUTE,
	array('Auth\Listener', 'applicationPostRoute'));
