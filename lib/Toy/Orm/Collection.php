<?php
namespace Toy\Orm;

use Toy\Collection\TEnumerator;
use Toy\Collection\TList;
use Toy\Data\Helper;

class Collection extends Query implements \Iterator, \ArrayAccess, \SeekableIterator, \Serializable, \Countable
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

//    public function hasModel($id){
//        foreach ($this->source as $item) {
//            if ($item->getIdValue() == $id) {
//                return true;
//            }
//        }
//        return false;
//    }
//
//    public function findModel($value)
//    {
//        foreach ($this->source as $item) {
//            if ($item->getIdValue() == $value) {
//                return $item;
//            }
//        }
//        return null;
//    }

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