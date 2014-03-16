<?php
\Toy\Event\Configuration::addListener(
	\Toy\Web\Application::APPLICATION_ON_START,
	array('\Localization\Listener', 'applicationOnStart'));
\Toy\Joy::addHelper('languages', \Localization\Dictionary::singleton());
\Toy\Joy::addHelper('localize', \Localization\Localize::singleton());
