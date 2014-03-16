<?php

namespace Toy\Data\Sql;

class DeleteStatement extends WhereStatement{

    protected $table = null;

    public function __construct($table = null){
        $this->table = $table;
    }

    public function from($table){
        $this->table = $table;
    }

    public function getTable(){
        return $this->table;
    }
}