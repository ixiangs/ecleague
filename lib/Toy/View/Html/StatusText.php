<?php
namespace Toy\View\Html;

class StatusText extends Element
{
    private $options = array();
    private $value = null;

    public function __construct($attrs = array())
    {
        parent::__construct('span', $attrs);
    }

    public function getCode()
    {
        return $this->value;
    }

    public function setCode($value)
    {
        $this->value = $value;
        return $this;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function setOptions($value)
    {
        $this->options = $value;
        return $this;
    }

    public function renderInner($data = array())
    {
        return $this->options[$this->value];
    }
}