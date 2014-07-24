<?php
namespace Toy\Html;

class TreeSelect extends Select
{

    private $_sortedOptions = array();
    private $_root = null;

    public function __construct($attrs = array())
    {
        parent::__construct($attrs);
    }

    public function setRoot($value, $text)
    {
        $this->_root = array('value' => $value, 'text' => $text);
        return $this;
    }

    public function getRoot()
    {
        return $this->_root;
    }

    public function renderInner()
    {
        $level = 0;
        $html = array();
        if (!is_null($this->caption)) {
            $html[] = '<option value="">' . $this->caption . '</option>';
        }

        if (!is_null($this->_root)) {
            $level = 1;
            if ($this->value == $this->_root['value']) {
                $html[] = '<option value="' . $this->_root['value'] . '" selected>' . $this->_root['text'] . '</option>';
            } else {
                $html[] = '<option value="' . $this->_root['value'] . '">' . $this->_root['text'] . '</option>';
            }
        }

        $this->sortOptions(0, $level);
        foreach ($this->_sortedOptions as $option) {
            $text = str_repeat('- ', $option['level']) . $option['text'];
            if ($this->value == $option['value']) {
                $html[] = '<option value="' . $option['value'] . '" selected>' . $text . '</option>';
            } else {
                $html[] = '<option value="' . $option['value'] . '">' . $text . '</option>';
            }
        }
        return implode('', $html);
    }

    private function sortOptions($parentId = 0, $level = 0)
    {
        for($i = 0; $i < count($this->options); $i++){
            $option = $this->options[$i];
            if ($option['parentId'] == $parentId) {
                $option['level'] = $level;
                $this->_sortedOptions[] = $option;
                $this->sortOptions($option['id'], ++$level);
                --$level;
            }
        }
    }
}