<?php
\Toys\Event\Configuration::addListener(
	\Toys\Web\Application::APPLICATION_ON_START,
	array('Auth\Listener', 'applicationOnStart'));
\Toys\Event\Configuration::addListener(
	\Toys\Web\Application::APPLICATION_POST_ROUTE,
	array('Auth\Listener', 'applicationPostRoute'));
