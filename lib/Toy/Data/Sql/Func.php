<?php

namespace Toy\Data\Sql;

class Func{

    public $name = null;
    public $arguments = null;

    public function __construct($name, array $args = array()){
        $this->name = $name;
        $this->arguments = $args;
    }

    public static function count($field){
        return new self()
    }
}