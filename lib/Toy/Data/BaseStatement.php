<?php

namespace Toy\Data;

abstract class BaseStatement {

    protected $parameters = array();

    protected function __construct(){}

    public function getParameters(){
        return $this->parameters;
    }
}