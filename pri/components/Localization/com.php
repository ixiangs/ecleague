<?php
\Toys\Event\Configuration::addListener(
	\Toys\Web\Application::APPLICATION_ON_START,
	array('\Localization\Listener', 'applicationOnStart'));
\Toys\Joy::addHelper('languages', \Localization\Dictionary::singleton());
\Toys\Joy::addHelper('localize', \Localization\Localize::singleton());
