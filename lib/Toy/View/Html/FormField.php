<?php
namespace Toy\View\Html;

abstract class FormField extends Element
{
    protected $label = null;
    protected $input = null;
    protected $validateRules = array();
    protected $description = null;
    protected $labelVisiable = true;

    public function __construct($label)
    {
        parent::__construct('div', array(
            'class' => 'form-group'
        ));
        $this->label = $label;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setLabel($value)
    {
        $this->label = $value;
        return $this;
    }

    public function getInput()
    {
        return $this->input;
    }

    public function setInput($value)
    {
        $this->input = $value;
        return $this;
    }

    public function setLabelVisiable($value)
    {
        $this->labelVisiable = $value;
        return $this;
    }

    public function getLabelVisiable()
    {
        return $this->labelVisiable;
    }

    public function getValidateRules()
    {
        return $this->validateRules;
    }

    public function addValidateRule($name, $value, $msg = null)
    {
        if (!is_null($msg)) {
            $this->validateRules[$name] = array('value' => $value, 'message' => $msg);
        } else {
            $this->validateRules[$name] = $value;
        }

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($value)
    {
        $this->description = $value;
        return $this;
    }

    public function render()
    {
        if (!is_null($this->renderer)) {
            return call_user_func($this->renderer, $this);
        }

        $html = array($this->renderBegin());
        if($this->labelVisiable){
//            $html[] = '<label class="col-sm-1 control-label">' . $this->label . '</label>';
            $html[] = '<label class="control-label">' . $this->label . '</label>';
        }
//        $html[] = '<div class="col-md-11">';
        $html[] = $this->renderInput();
//        $html[] = '</div>';
        $html[] = $this->renderEnd();

        return implode('', $html);
    }

    abstract protected function renderInput();
}