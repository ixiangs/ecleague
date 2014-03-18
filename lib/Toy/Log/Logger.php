<?php
namespace Toy\Log;

class Logger
{

    const LEVEL_ERROR = 4;
    const LEVEL_WARNING = 3;
    const LEVEL_DEBUG = 2;
    const LEVEL_INFO = 1;
    const LEVEL_VERBOSE = 0;

    private static $_levelLabels = array('v', 'i', 'd', 'w', 'e');
    private static $_appender = null;

    private function __construct()
    {
    }

    public function e($content, $type = null)
    {
        $this->write($content, self::LEVEL_ERROR, $type);
    }

    public function w($content, $type = null)
    {
        $this->write($content, self::LEVEL_WARNING, $type);
    }

    public function d($content, $type = null)
    {
        $this->write($content, self::LEVEL_DEBUG, $type);
    }

    public function i($content, $type = null)
    {
        $this->write($content, self::LEVEL_INFO, $type);
    }

    public function v($content, $type = null)
    {
        $this->write($content, self::LEVEL_VERBOSE, $type);
    }

    private function write($content, $level, $type = null)
    {
        if (Configuration::$level >= $level) {
            $str = sprintf("%s|%s|%s|%s|%s\n",
                self::$_levelLabels[$level],
                date('Y-m-d H:i:s'),
                (array_key_exists('REMOTE_ADD', $_SERVER)? $_SERVER['REMOTE_ADD']: 'localhost'),
                empty($type) ? '-' : $type,
                $content);
            self::$_appender->append($str);
        }
    }

    private static $_instance = null;

    static public function singleton()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
//            $os = Configuration::$outputSettings[Configuration::$defaultOutput];
            self::$_appender = new Configuration::$appender(); //new $os['class']($os);
        }
        return self::$_instance;
    }
}