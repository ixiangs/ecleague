<?php
namespace Core\Html\Widget;

abstract class BaseField
{

    private $_label = null;
    private $_inputId = null;
    private $_inputName = null;
    private $_value = null;
    private $_inputAttributes = array();
    private $_validateRules = array();

    public function __construct($label, $inputId, $inputName, $value = null)
    {
        $this->_label = $label;
        $this->_inputId = $inputId;
        $this->_inputName = $inputName;
        $this->_value = $value;
    }

    public function getLabel()
    {
        return $this->_label;
    }

    public function setLabel($value)
    {
        $this->_label = $value;
        return $this;
    }

    public function getInputId()
    {
        return $this->_inputId;
    }

    public function setInputId($value)
    {
        $this->_inputId = $value;
        return $this;
    }

    public function getInputName()
    {
        return $this->_inputName;
    }

    public function setInputName($value)
    {
        $this->_inputName = $value;
        return $this;
    }

    public function getValue()
    {
        return $this->_value;
    }

    public function setValue($value)
    {
        $this->_value = $value;
        return $this;
    }

    public function getValidateRules()
    {
        return $this->_validateRules;
    }

    public function addValidateRule($name, $value)
    {
        $this->_validateRules[$name] = $value;
        return $this;
    }

    public function getInputAttributes()
    {
        return $this->_inputAttributes;
    }

    public function setInputAttributes($value)
    {
        $this->_inputAttributes = $value;
        return $this;
    }

    public function render()
    {
        $html = array('<div class="form-group">');
        $html[] = '<label class="col-lg-2 control-label" for="' . $this->_inputId . '">' . $this->_label . '</label>';
        $html[] = '<div class="col-lg-10">'.$this->renderInput().'</div>';
        $html[] = '</div>';

        return implode('', $html);
    }

    abstract protected function renderInput();
}