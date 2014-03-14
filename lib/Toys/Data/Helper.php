<?php

namespace Toys\Data;


class Helper
{

    private static $_dbs = array();

    public static function createDb($name = null)
    {
        if (is_null($name)) {
            $name = Configuration::$defaultConnection;
        }
        $s = Configuration::$connectionSettings[$name];
        return new $s['provider']($s['settings']);
    }

    public static function openDb($name = null)
    {
        if (is_null($name)) {
            $name = Configuration::$defaultConnection;
        }
        if (!array_key_exists($name, self::$_dbs)) {
            $_dbs[$name] = self::createDb($name);
            $_dbs[$name]->open();
        }
        return $_dbs[$name];
    }

    public static function withDb(\Closure $callback, $name = null)
    {
        try {
            $db = self::createDb($name);
            $db->open();
            $callback($db);
        } finally {
            $db->close();
        }
    }

    public static function withTx(\Closure $callback, $name = null)
    {
        try {
            $db = self::createDb($name);
            $db->open();
            $db->begin();
            $callback($db);
            $db->commit();
        } catch (\Exception $ex) {
            $db->rollback();
            throw $ex;
        } finally {
            $db->close();
        }
    }
}