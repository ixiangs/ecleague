<?php
namespace Toy;

class Event
{
    static public $observers = array();

    static public function attach($event, $observer)
    {
        if (!array_key_exists($event, self::$observers)) {
            self::$observers[$event] = array();
        }
        self::$observers[$event][] = $observer;
    }

    static public function dispatch($event, $source, &$argument = null)
    {
        if (array_key_exists($event, self::$observers)) {
            foreach (self::$observers[$event] as $observer) {
                call_user_func_array($observer, array($source, $argument));
            }
        }
    }
}
