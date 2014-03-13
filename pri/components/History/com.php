<?php
\Toys\Event\Configuration::addListener(
	\Toys\Framework\Application::APPLICATION_ON_START, 
	array('\History\Listener', 'applicationOnStart'));
\Toys\Joy::addHelper('history', \History\Recorder::singleton());
