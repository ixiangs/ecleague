<?php
namespace Toy\Collection;

trait TEnumerator
{

    public function current()
    {
        return current($this->source);
    }

    public function key()
    {
        return key($this->source);
    }

    public function next()
    {
        next($this->source);
    }

    public function rewind()
    {
        reset($this->source);
    }

    public function valid()
    {
        return current($this->source) !== false;
    }

    public function isEmpty()
    {
        return count($this->source) == 0;
    }
}