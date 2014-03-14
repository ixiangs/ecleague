<?php

namespace Toys\Data\Sql;

class UpdateStatement extends WhereStatement{

    protected $values = array();
    protected $table = null;

    public function __construct($table = null, array $values = array()){
        $this->table = $table;
        $this->values = $values;
    }

    public function getValues(){
        return $this->values;
    }

    public function getTable(){
        return $this->table;
    }

    public function into($table){
        $this->table = $table;
    }

    public function setValue($field, $value){
        $this->values[$field] = $value;
    }
}