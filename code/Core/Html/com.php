<?php
\Toy\Event\Configuration::addListener(
	\Toy\Web\Application::APPLICATION_ON_START,
	array('\Html\Listener', 'applicationOnStart'));
\Toy\Joy::addHelper('html', \Html\Helper::singleton());
