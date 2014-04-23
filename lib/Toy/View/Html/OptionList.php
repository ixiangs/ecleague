<?php
namespace Toy\View\Html;

class OptionList extends Element
{
    private $_options = array();
    private $_multiple = true;

    public function __construct()
    {
        parent::__construct(null);
    }

    public function setOptions($value)
    {
        $this->_options = $value;
        return $this;
    }

    public function getOptions()
    {
        return $this->_options;
    }

    public function setMultiple($value)
    {
        $this->_multiple = $value;
        return $this;
    }

    public function getMultiple()
    {
        return $this->_multiple;
    }

    public function renderBegin()
    {
        return '';
    }

    public function renderEnd()
    {
        return '';
    }

    public function renderInner()
    {
        $val = $this->getAttribute('value');
        $this->removeAttribute('value');
        $this->setAttribute('type', $this->_multiple? 'checkbox': 'radio');
        $html = array();
        foreach ($this->_options as $option => $text) {
            $this->setAttribute('value', $option);
            if (is_array($val) && in_array($option, $val)) {
                $this->setAttribute('checked', 'checked');
                $html[] = sprintf('<label class="checkbox-inline"><input %s/>%s</label>', $this->renderAttribute(), $text);
            } else {
                $html[] = sprintf('<label class="checkbox-inline"><input %s/>%s</label>', $this->renderAttribute(), $text);
            }
            $this->removeAttribute('checked');
        }
        return implode('', $html);
    }
}