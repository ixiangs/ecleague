<?php
namespace Toy\Collection;

abstract class Enumerator implements \Iterator{

    protected $source = NULL;

    protected function __construct($source = array()){
        $this->source = $source;
    }

    public function getSource(){
        return $this->source;
    }

    public function toArray(Closure $function = NULL) {
    	if(is_callable($function)){
	        $result = array();
	        foreach ($this as $index=>$item) {
				$result[] = $function($item, $index);
	        }
	        return $result;
		}

		$result = array();
        foreach ($this->source as $index=>$item) {
        	$result[$index] = $item;
        }
		return $result;
    }

    public function current() {
        return current($this->source);
    }

    public function key() {
        return key($this->source);
    }

    public function next() {
        next($this->source);
    }

    public function rewind() {
        reset($this->source);
    }

    public function valid() {
        return current($this->source) !== false;
    }

    public function isEmpty(){
        return count($this->source) == 0;
    }
}