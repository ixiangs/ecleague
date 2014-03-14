<?php
namespace Toys\Log;

class Configuration{
	public static $level = Logger::LEVEL_VERBOSE;
	public static $appender = '\Toys\Log\ConsoleAppender';
	public static $settings = array();
}
