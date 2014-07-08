<?php
namespace Toy\Html;

class OptionList extends InputElement
{
    private $_options = array();
    private $_multiple = true;

    public function __construct(array $attrs)
    {
        parent::__construct('div', $attrs);
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

    public function render()
    {
        if (!is_null($this->renderer)) {
            $r = call_user_func($this->renderer, $this);
            return $r;
        }

        $val = $this->getAttribute('value');
        $this->removeAttribute('value', 'class');
        $this->setAttribute('type', $this->_multiple ? 'checkbox' : 'radio');
        $html = array('');
        foreach ($this->_options as $option => $text) {
            $this->setAttribute('value', $option);
            if (is_array($val) && in_array($option, $val)) {
                $this->setAttribute('checked', 'checked');
                $html[] = sprintf('<label class="checkbox-inline col-lg-2"><input %s/>%s</label>', $this->renderAttribute(), $text);
            } else {
                $html[] = sprintf('<label class="checkbox-inline col-lg-2"><input %s/>%s</label>', $this->renderAttribute(), $text);
            }
            $this->removeAttribute('checked');
        }
//        $html[] = '<div class="clearfix"></div></div>';
        return implode('', $html);
    }
}