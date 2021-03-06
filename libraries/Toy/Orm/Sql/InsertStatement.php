<?php

namespace Toy\Orm\Sql;

use Toy\Orm\Db\Helper;

class InsertStatement extends BaseStatement
{

    protected $values = array();
    protected $table = null;

    public function __construct($table = null, array $values = array())
    {
        $this->table = $table;
        $this->values = $values;
    }

    public function getValues()
    {
        return $this->values;
    }

    public function getTable()
    {
        return $this->table;
    }

    public function setTable($value)
    {
        $this->table = $value;
    }

    public function setValue($field, $value)
    {
        $this->values[$field] = $value;
    }

    public function execute($db = null)
    {
        $cdb = is_null($db) ? Helper::openDb() : $db;
        return $cdb->insert($this);
    }

    public function executeLastInsertId($db = null)
    {
        $cdb = is_null($db) ? Helper::openDb() : $db;
        if ($cdb->insert($this)) {
            return $cdb->getLastInsertId();
        }
        return 0;
    }
}