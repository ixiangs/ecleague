<?php
namespace Toy\Orm;

use Toy\Collection\TEnumerator;
use Toy\Collection\TList;
use Toy\Db\Helper;
use Toy\Db\SelectStatement;

class Collection extends SelectStatement implements \Iterator, \ArrayAccess, \SeekableIterator, \Serializable, \Countable
{
    use TList;

    protected $source = array();

    private $_itemClass = null;

    public function __construct($itemClass = null, $source = array())
    {
        $this->_itemClass = $itemClass;
        foreach ($source as $row) {
            $m = new $this->_itemClass();
            $m->fillRow($row);
            $this->source[] = $m;
        }
    }

    public function getItemClass()
    {
        return $this->_itemClass;
    }

    public function setItemClass($value)
    {
        $this->_itemClass = $value;
        return $this;
    }

    public function load($db = null)
    {
        $cdb = $db ? $db : Helper::openDb();
        $rows = $cdb->select($this)->rows;
        foreach ($rows as $row) {
            $m = new $this->_itemClass();
            $m->fillRow($row);
            $this->source[] = $m;
        }
        return $this;
    }
}