<?php
namespace Toy\View\Html;

class FormField extends Element
{
    protected $label = null;
    protected $input = null;
    protected $required = false;
    protected $validateRules = array();
    protected $description = null;

    public function __construct($label)
    {
        parent::__construct('div', array(
            'class' => 'form-group'
        ));
        $this->label = $label;
    }

    public function getRequired()
    {
        return $this->required;
    }

    public function setRequired($value)
    {
        $this->required = $value;
        return $this;
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
        $html[] = '<label class="control-label">' . $this->label . '</label>';
        $html[] = $this->input->render();
        $html[] = $this->renderEnd();

        return implode('', $html);
    }
}