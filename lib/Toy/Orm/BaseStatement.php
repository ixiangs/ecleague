<?php

namespace Toy\Orm\Db;

abstract class BaseStatement
{

    protected $parameters = array();

    protected function __construct()
    {
    }

    public function getParameters()
    {
        return $this->parameters;
    }
}