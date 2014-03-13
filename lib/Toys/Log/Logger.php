<?php
namespace Toys\Log;

use Toys\Util\ArrayUtil;

class Logger
{

    const LEVEL_ERROR = 4;
    const LEVEL_WARNING = 3;
    const LEVEL_DEBUG = 2;
    const LEVEL_INFO = 1;
    const LEVEL_VERBOSE = 0;

    private static $_levelLabels = array('v', 'i', 'd', 'w', 'e');
    private static $_output = null;

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
                ArrayUtil::get($_SERVER, 'REMOTE_ADDR', 'localhost'),
                empty($type) ? '-' : $type,
                $content);
            self::$_output->write($str);
        }
    }

    private static $_instance = null;

    public static function singleton()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
            $os = Configuration::$outputSettings[Configuration::$defaultOutput];
            self::$_output = new $os['class']($os);
        }
        return self::$_instance;
    }
}