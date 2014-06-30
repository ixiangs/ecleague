<?php
namespace Toy\Orm\Db\Driver;

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

    public abstract function isConnected();

    public abstract function inTransaction();

    public abstract function escape($value);

    public abstract function open();

    public abstract function close();

    public abstract function begin();

    public abstract function commit();

    public abstract function rollback();

    public abstract function getLastInsertId();

    public abstract function execute($sql, $arguments = array());

    public abstract function fetch($sql, $arguments = array());
}
