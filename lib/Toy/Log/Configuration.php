<?php
namespace Toy\Log;

class Configuration{
	public static $level = Logger::LEVEL_VERBOSE;
	public static $appender = '\Toy\Log\ConsoleAppender';
	public static $settings = array();
}
