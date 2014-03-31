<?php
namespace Core\Html\Widget;

class CheckboxList extends Element
{
    private $_options = array();

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
        $this->setAttribute('type', 'checkbox');
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