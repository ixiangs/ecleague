<?php

namespace Toy\Db;

class DeleteStatement extends WhereStatement
{

    protected $table = null;

    public function __construct($table = null)
    {
        $this->table = $table;
    }

    public function setTable($value)
    {
        $this->table = $value;
        return $this;
    }

    public function getTable()
    {
        return $this->table;
    }

    public function execute($db = null)
    {
        $cdb = is_null($db) ? Helper::openDb() : $db;
        return $cdb->delete($this);
    }
}