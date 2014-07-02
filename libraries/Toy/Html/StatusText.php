<?php
namespace Toy\Html;

use Toy\Util\StringUtil;

class StatusText extends Element
{
    private $_items = array();

    public function __construct($attrs = array())
    {
        parent::__construct('span', $attrs);
    }

    public function getItems()
    {
        return $this->_items;
    }

    public function setItems($value)
    {
        $this->_items = $value;
        return $this;
    }

    public function renderInner($data = array())
    {
        $txt = $this->attributes['text'];
        $txt = $txt[0] == '@' ? StringUtil::substitute(substr($txt, 1), $data) : $txt;
        return $this->_items[$txt];
    }
}