<?php
namespace Toy\Collection;

class ArrayList implements \Iterator, \ArrayAccess, \SeekableIterator, \Serializable, \Countable
{
    protected $source = array();

    use TList;

    public function __construct($source = array())
    {
        $this->source = $source;
    }
}