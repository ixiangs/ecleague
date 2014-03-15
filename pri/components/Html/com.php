<?php
\Toys\Event\Configuration::addListener(
	\Toys\Web\Application::APPLICATION_ON_START,
	array('\Html\Listener', 'applicationOnStart'));
\Toys\Joy::addHelper('html', \Html\Helper::singleton());
