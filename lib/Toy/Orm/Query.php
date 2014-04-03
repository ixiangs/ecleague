<?php
namespace Toy\Orm;

use Toy\Data\Helper;
use Toy\Data\Sql\SelectStatement;

class Query extends SelectStatement
{

    private $_modelClass = null;

    public function __construct($modelClass)
    {
        $this->_modelClass = $modelClass;
    }

    public function getModelClass()
    {
        return $this->_modelClass;
    }

    public function setModelClass($value)
    {
        $this->_modelClass = $value;
        return $this;
    }

    public function execute($db = null)
    {
        $cdb = $db ? $db : Helper::openDb();
        return new Result($this->_modelClass, $cdb->select($this)->rows);
    }
}