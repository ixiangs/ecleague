<?php
namespace Toy\View\Html;

use Toy\Util\StringUtil;

class OptionColumn extends LabelColumn
{
    private $_options = array();

    public function setOptions($value)
    {
        $this->_options = $value;
        return $this;
    }

    public function getOptions()
    {
        return $this->_options;
    }

    public function renderCell($row, $index)
    {
        if (!is_null($this->cellRenderer)) {
            return call_user_func_array($this->cellRenderer, array($this, $row, $index));
        }

        $label = $this->getCell()->getChild(0);
        $st = $label->getAttribute('text');
        $op = StringUtil::substitute($st, $row);
        $label->setAttribute('text', array_key_exists($op, $this->_options) ? $this->_options[$op] : $this->getDefaultText());
        $res = parent::renderCell($row, $index);
        $label->setAttribute('text', $st);
        return $res;
    }
}