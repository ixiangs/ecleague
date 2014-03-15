<?php
namespace Toys\Event;

class Configuration
{

    public static $events = array();
    public static $listeners = array();

    public static function addEvent()
    {
        $args = func_get_args();
        self::$events = array_merge(self::$events, $args);
    }

    public static function addListener($event, $handler)
    {
        if (!array_key_exists($event, self::$listeners)) {
            self::$listeners[$event] = array();
        }
        self::$listeners[$event][] = $handler;
    }
}
