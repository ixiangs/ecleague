<?php
\Toy\Event\Configuration::addListener(
	\Toy\Web\Application::APPLICATION_ON_START,
	array('\History\Listener', 'applicationOnStart'));
\Toy\Joy::addHelper('history', \History\Recorder::singleton());
