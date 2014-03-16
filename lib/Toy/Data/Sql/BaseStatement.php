<?php

namespace Toy\Data\Sql;

abstract class BaseStatement {

    protected $parameters = array();

    protected function __construct(){}

    public function getParameters(){
        return $this->parameters;
    }
}