<?php
namespace Toy\View\Html;

class Select extends InputElement
{

    protected $caption = null;
    protected $options = array();
    protected $value = null;

    public function __construct($attrs = array())
    {
        parent::__construct('select', $attrs);
    }

    public function getCaption()
    {
        return $this->caption;
    }

    public function setCaption($value)
    {
        $this->caption = $value;
        return $this;
    }

    public function setOptions($value)
    {
        $this->options = $value;
        return $this;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function renderBegin()
    {
        if (array_key_exists('value', $this->attributes)) {
            $this->value = $this->attributes['value'];
            $this->removeAttribute('value');
        }
        return parent::renderBegin();
    }

    public function renderInner()
    {
        if (!is_null($this->caption)) {
            if (is_string($this->caption)) {
                $html[] = '<option value="">' . $this->caption . '</option>';
            }
        }

        foreach ($this->options as $option => $text) {
            if ($this->value == $option) {
                $html[] = "<option value=\"$option\" selected>$text</option>";
            } else {
                $html[] = "<option value=\"$option\">$text</option>";
            }
        }
        return implode('', $html);
    }
}