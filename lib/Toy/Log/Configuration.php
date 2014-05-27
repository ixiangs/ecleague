<?php
namespace Toy\Log;

class Configuration
{
    static public $level = Logger::LEVEL_VERBOSE;
    static public $appender = '\Toy\Log\ConsoleAppender';
    static public $settings = array();
}
