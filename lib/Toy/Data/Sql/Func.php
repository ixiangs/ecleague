<?php

namespace Toy\Data\Sql;

class Func{

    public $name = null;
    public $arguments = null;

    public function __construct($name, array $args = array()){
        $this->name = $name;
        $this->arguments = $args;
    }

    public static function count($field = '*'){
        return new self('count', array('field'=>$field));
    }

    public static function max($field = '*'){
        return new self('max', array('field'=>$field));
    }

    public static function min($field = '*'){
        return new self('min', array('field'=>$field));
    }
}