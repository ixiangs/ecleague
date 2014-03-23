<?php
namespace Core\Html\Widget;

abstract class BaseField extends Element
{
    private $_label = null;
    private $_inputId = null;
    private $_inputName = null;
    private $_value = null;
    private $_inputAttributes = array();
    private $_validateRules = array();

    public function __construct($label, $inputId, $inputName, $value = null)
    {
        parent::__construct('div', array(
            'class'=>'form-group'
        ));
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

//    public function getFieldId(){
//        return $this->_fieldId;
//    }
//
//    public function setFieldId($value){
//        $this->_fieldId = $value;
//        return $this;
//    }
//
//    public function getFieldIdFormat(){
//        return $this->_fieldIdFormat;
//    }
//
//    public function setFieldIdFormat($value){
//        $this->_fieldIdFormat = $value;
//        return $this;
//    }
//
//    public function getFieldCss(){
//        return $this->_fieldCss;
//    }
//
//    public function setFieldCss($value){
//        $this->_fieldCss = $value;
//        return $this;
//    }
//
//    public function getFieldCssFormat(){
//        return $this->_fieldCssFormat;
//    }
//
//    public function setFieldCssFormat($value){
//        $this->_fieldCssFormat = $value;
//        return $this;
//    }

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
        $html = array($this->renderBegin());
        $html[] = '<label class="col-lg-2 control-label" for="' . $this->_inputId . '">' . $this->_label . '</label>';
        $html[] = '<div class="col-lg-10">'.$this->renderInput().'</div>';
        $html[] = $this->renderEnd();

        return implode('', $html);
    }

    abstract protected function renderInput();
}