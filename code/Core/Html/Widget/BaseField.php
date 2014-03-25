<?php
namespace Core\Html\Widget;

abstract class BaseField extends Element
{
    private $_label = null;
    private $_validateRules = array();

    public function __construct($label)
    {
        parent::__construct('div', array(
            'class'=>'form-group'
        ));
        $this->_label = $label;
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

    public function getValidateRules()
    {
        return $this->_validateRules;
    }

    public function addValidateRule($name, $value)
    {
        $this->_validateRules[$name] = $value;
        return $this;
    }

    public function render()
    {
        $html = array($this->renderBegin());
        $html[] = '<label class="col-lg-2 control-label">' . $this->_label . '</label>';
        $html[] = '<div class="col-lg-10">'.$this->renderInput().'</div>';
        $html[] = $this->renderEnd();

        return implode('', $html);
    }

    abstract protected function renderInput();
}