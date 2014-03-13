<?php
\Toys\Event\Configuration::addListener(
	\Toys\Framework\Application::APPLICATION_ON_START, 
	array('User\Listener', 'applicationOnStart'));
\Toys\Event\Configuration::addListener(
	\Toys\Framework\Application::APPLICATION_POST_ROUTE,
	array('User\Listener', 'applicationPostRoute'));
