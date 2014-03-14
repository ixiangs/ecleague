<?php
namespace Toys\Orm;

use Toys\Collection\ArrayList;

class Result extends \Toys\Data\Result
{

    private $_entity = null;

    public function __construct($entity, $source)
    {
        parent::__construct($source);
        $this->_entity = $entity;
    }

    public function getFirstModel()
    {
        $row = $this->getFirstRow();
        if ($row) {
            $result = $this->_entity->newModel();
            $result->fillRow($row);
            return $result;
        }
        return null;
    }

    public function getModelArray()
    {
        $result = array();
        foreach ($this->rows as $row) {
            $m = $this->_entity->newModel();
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
