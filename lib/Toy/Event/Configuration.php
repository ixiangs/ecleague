<?php
namespace Toy\Event;

class Configuration
{

    static public $events = array();
    static public $listeners = array();

    static public function addEvent()
    {
        $args = func_get_args();
        self::$events = array_merge(self::$events, $args);
    }

    static public function addListener($event, $handler)
    {
        if (!array_key_exists($event, self::$listeners)) {
            self::$listeners[$event] = array();
        }
        self::$listeners[$event][] = $handler;
    }
}
