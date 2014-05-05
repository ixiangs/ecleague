<?php

namespace Toy\Data;


use Toy\Data\Sql\UpdateStatement;

class Helper
{

    private static $_dbs = array();

    static public function createDb($name = null)
    {
        if (is_null($name)) {
            $name = Configuration::$defaultConnection;
        }
        $s = Configuration::$connectionSettings[$name];
        return new Database($s);
    }

    static public function openDb($name = null)
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

    static public function withDb(\Closure $callback, $name = null)
    {
        try {
            $db = self::createDb($name);
            $db->open();
            $callback($db);
        } finally {
            $db->close();
        }
    }

    static public function withTx(\Closure $callback, \Closure $failure = null, $name = null)
    {
        try {
            $db = self::createDb($name);
            $db->open();
            $result = $callback($db);
            $db->begin();
            $db->commit();
            return $result;
        } catch (\Exception $ex) {
            $db->rollback();
            if ($failure != null) {
                return $failure($ex);
            }
            throw $ex;
        } finally {
            $db->close();
        }
    }

    static public function insert($table, array $values = array()){
        return new InsertStatement($table, $values);
    }

    static public function update($table, array $values = array()){
        return new UpdateStatement($table, $values);
    }

    static public function delete($table){
        return new DeleteStatement($table);
    }

    static public function select($table, array $fields = array()){
        return new SelectStatement($table, $fields);
    }
}