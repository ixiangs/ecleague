<?php
namespace Core\Html\Widget;

class Select extends Element
{

    private $_caption = null;
    private $_options = null;
    private $_value = null;

    public function __construct($attrs = array())
    {
        parent::__construct('select', $attrs);
    }

    public function setCaption($value)
    {
        $this->_caption = $value;
        return $this;
    }

    public function getCaption()
    {
        return $this->_caption;
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

    public function renderBegin(){
        if(array_key_exists('value', $this->attributes)){
            $this->_value = $this->attributes['value'];
            $this->removeAttribute('value');
        }
        return parent::renderBegin();
    }

    public function renderInner()
    {
        if (!empty($this->_caption)) {
            if (is_string($this->_caption)) {
                $html[] = '<option value="">' . $this->_caption . '</option>';
            }
        }

        foreach ($this->_options as $option => $text) {
            if ($this->_value == $option) {
                $html[] = "<option value=\"$option\" selected>$text</option>";
            } else {
                $html[] = "<option value=\"$option\">$text</option>";
            }
        }
        return implode('', $html);
    }
}