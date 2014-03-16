<?php
namespace Toy\Collection;

class ArrayList extends Enumerator implements \ArrayAccess, \SeekableIterator, \Serializable, \Countable {

    public function __construct($source = array()) {
        parent::__construct($source);
    }

    public function getItem($offset) {
        return $this->source[$offset];
    }

    public function getFirst() {
        $result = $this->getItem(0);
        return $result === false? null: $result;
    }

    public function getLast() {
        return end($this->source);
    }

    public function hasItem($entity) {
        foreach ($this as $item) {
            if ($item == $entity){
                return TRUE;
            }
        }
        return false;
    }

    public function insert($item, $index = NULL) {
        if (is_null($index)) {
            $this->append($item);
        } else {
            $temp1 = array_slice($this->source, 0, $index);
            $temp2 = array_slice($this->source, $index);
            $temp1[] = $item;
            $this->source = array_merge($temp1, $temp2);
        }
        return $this;
    }

    public function append($item) {
        $this->source[] = $item;
        return $this;
    }

    public function merge(array $items){
        foreach($items as $item){
            $this->source[] = $item;
        }
        return $this;
    }

    public function remove($offset) {
        unset($this->source[$offset]);
        return $this;
    }

    public function clear() {
        $this->source = array();
        return $this;
    }

    public function pop() {
        array_pop($this->source);
    }
    
    public function each(\Closure $function) {
        foreach ($this->source as $index=>$item) {
            $function($item, $index);
        }
        return $this;
    }

    public function find(Closure $function) {
        foreach ($this->source as $index=>$item) {
            if ($function($item, $index)) {
                return $item;
            }
        }
        return NULL;
    }

    public function filter(Closure $function) {
        $result = new self();
        foreach ($this->source as $index=>$item) {
            if ($function($item, $index)) {
                $result->append($item);
            }
        }
        return $result;
    }

    public function contains(Closure $function) {
        foreach ($this->source as $index=>$item) {
            if ($function($item, $index)) {
                return TRUE;
            }
        }
        return false;
    }

    public function count() {
        return count($this->source);
    }

    public function offsetExists($offset) {
        return isset($this->source[$offset]);
    }

    public function offsetGet($offset) {
        return $this->getItem($offset);
    }

    public function offsetSet($offset, $value) {
        $this->append($value, $offset);
    }

    public function offsetUnset($offset) {
        unset($this->source[$offset]);
    }

    public function seek($position) {
        throw new Soul_Exception('The seek method not support');
    }

    public function serialize() {
        return serialize($this->source);
    }

    public function unserialize($serialized) {
        $arr = unserialize($serialized);
        return new self($arr);
    }

}