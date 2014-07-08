<?php
namespace Toy\Orm;

use Toy\Collection\TEnumerator;
use Toy\Collection\TList;
use Toy\Orm\Db\Helper;
use Toy\Orm\Sql\SelectStatement;

class Query extends SelectStatement implements \Iterator, \ArrayAccess, \SeekableIterator, \Serializable, \Countable
{
    use TList;

    protected $source = array();

    private $_modelClass = null;

    public function __construct($modelClass = null, $source = array())
    {
        $this->_modelClass = $modelClass;
        foreach ($source as $row) {
            $m = new $this->_modelClass();
            $m->setAllData($row);
            $this->source[] = $m;
        }
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

    public function load($db = null)
    {
        $rows = $this->fetch($db)->rows;
        foreach ($rows as $row) {
            $m = new $this->_modelClass();
            $m->fromDbRow($row)->markClean();
            $this->source[] = $m;
        }
        return $this;
    }
}