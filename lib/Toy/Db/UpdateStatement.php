<?php

namespace Toy\Db;

class UpdateStatement extends WhereStatement
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

    public function setValue($field, $value)
    {
        $this->values[$field] = $value;
    }

    public function getTable()
    {
        return $this->table;
    }

    public function setTable($value)
    {
        $this->table = $value;
    }

    public function execute($db = null)
    {
        $cdb = is_null($db) ? Helper::openDb() : $db;
        return $cdb->update($this);
    }
}