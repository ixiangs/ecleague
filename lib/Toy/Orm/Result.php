<?php
namespace Toy\Orm;

use Toy\Collection\ArrayList;

class Result extends \Toy\Data\Result
{

    private $_modelClass = null;

    public function __construct($modelClass, $source)
    {
        parent::__construct($source);
        $this->_modelClass  = $modelClass;
    }

    public function getFirstModel()
    {
        $row = $this->getFirstRow();
        if ($row) {
            $result = new $this->_modelClass();
            $result->fillRow($row);
            return $result;
        }
        return null;
    }

    public function getModelArray()
    {
        $result = array();
        foreach ($this->rows as $row) {
            $m = new $this->_modelClass();
            $m->fillRow($row);
            $result[] = $m;
        }
        return $result;
    }

    public function getModelList()
    {
        return new ArrayList($this->getModelArray());
    }

}
