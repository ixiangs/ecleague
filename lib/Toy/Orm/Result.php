<?php
namespace Toy\Orm\Db;

class Result
{

    public $rows = null;

    public function __construct(array $source)
    {
        $this->rows = $source;
    }

    public function isEmpty()
    {
        return empty($this->rows) || count($this->rows) == 0;
    }

    public function rowCount()
    {
        return count($this->rows);
    }

    public function combineColumns($keyColumn, $valueColumn)
    {
        $result = array();
        if (!empty($this->rows) &&
            array_key_exists($keyColumn, $this->rows[0]) &&
            array_key_exists($valueColumn, $this->rows[0])
        ) {
            foreach ($this->rows as $v) {
                $result[$v[$keyColumn]] = $v[$valueColumn];
            }
        }
        return $result;
    }

    public function getColumnValues($col)
    {
        $result = array();
        if (!empty($this->rows) &&
            array_key_exists($col, $this->rows[0])
        ) {
            foreach ($this->rows as $v) {
                $result[] = $v[$col];
            }
        }
        return $result;
    }

    public function getFirstValue()
    {
        $fr = $this->getFirstRow();
        if ($fr != null) {
            reset($fr);
            return current($fr);
        }
        return null;
    }

    public function getFirstRow()
    {
        if (!$this->isEmpty()) {
            return $this->rows[0];
        }
        return null;
    }

}
