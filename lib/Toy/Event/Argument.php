<?php
namespace Toy\Event;

class Argument
{

    private $_cancelled = false;
    private $_data = null;

    public function __construct($data = null)
    {
        $this->_data = $data;
    }

    public function getData()
    {
        return $this->_data;
    }

    public function setData($value)
    {
        $this->_data = $value;
        return $this;
    }

    public function getCancelled()
    {
        return $this->_cancelled;
    }

    public function setCancelled($value)
    {
        $this->_cancelled = $value;
        return $this;
    }
}