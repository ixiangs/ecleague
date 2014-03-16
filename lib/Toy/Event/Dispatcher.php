<?php
namespace Toy\Event;

class Dispatcher
{

    public static function dispatch($event, $source, &$argument = null)
    {
        if (!in_array($event, Configuration::$events)) {
            throw new Exception('not found event [' . $event . ']');
        }

        if (array_key_exists($event, Configuration::$listeners)) {
            if (empty($argument)) {
                $argument = new Argument();
            }

            foreach (Configuration::$listeners[$event] as $handler) {
                if ($argument->getCancelled()) {
                    return;
                }
                call_user_func_array($handler, array($source, $argument));
            }
        }
    }

}
