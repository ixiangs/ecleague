<?php
namespace Toy\Orm\Db;

use \Toy\Orm\Configuration;

abstract class BaseDriver
{

    protected $settings = null;

    protected function __construct($settings)
    {
        $this->settings = $settings;
    }

    public function getSettings()
    {
        return $this->settings;
    }

    protected function log($sql, $arguments)
    {
        $content = $sql;
        foreach ($arguments as $n => $v) {
            $content .= '[' . $n . ':' . $v . ']';
        }
        Configuration::$logger->v($content, 'sql');
    }

    abstract public function isConnected();
    abstract public function inTransaction();
    abstract public function escape($value);
    abstract public function open();
    abstract public function close();
    abstract public function begin();
    abstract public function commit();
    abstract public function rollback();
    abstract public function getLastInsertId();
    abstract public function insert($statement);
    abstract public function update($statement);
    abstract public function delete($statement);
    abstract public function select($statement);
    abstract public function execute($sql, $arguments = array());
    abstract public function fetch($sql, $arguments = array());
}
