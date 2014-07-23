<?php
namespace Toy\Html;

class FormField extends Element
{
    protected $label = null;
    protected $input = null;
    protected $required = false;
    protected $validateRules = array();
    protected $lableVisible = true;
    protected $description = null;
    private $_leftAddon = null;
    private $_rightAddon = null;

    public function __construct($label)
    {
        parent::__construct('div', array(
            'class' => 'form-group'
        ));
        $this->label = $label;
    }

    public function getLeftAddon()
    {
        return $this->_leftAddon;
    }

    public function setLeftAddon($value)
    {
        $this->setAttribute('class', 'input-group');
        $this->_leftAddon = $value;
        return $this;
    }

    public function getRightAddon()
    {
        return $this->_rightAddon;
    }

    public function setRightAddon($value)
    {
        $this->setAttribute('class', 'input-group');
        $this->_rightAddon = $value;
        return $this;
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

    public function getLabelVisible(){
        return $this->lableVisible;
    }

    public function setLabelVisible($value){
        $this->lableVisible = $value;
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
        if($this->lableVisible){
            $html[] = '<label class="control-label">' . $this->label . '</label>';
        }

        if (!is_null($this->_leftAddon)) {
            if ($this->_leftAddon instanceof ButtonGroup) {
                $html[] = '<div class="input-group-btn">';
                $html[] = $this->_leftAddon->render();
                $html[] = '</div>';
            } elseif ($this->_leftAddon instanceof Element) {
                $html[] = '<div  class="input-group-btn">';
                $html[] = $this->_leftAddon->render();
                $html[] = '</div>';
            } else {
                $html[] = $this->_leftAddon;
            }
        }
        $html[] = $this->input->render();
        if (!is_null($this->_rightAddon)) {
            if ($this->_rightAddon instanceof ButtonGroup) {
                $html[] = '<div class="input-group-btn">';
                $html[] = $this->_leftAddon->render();
                $html[] = '</div>';
            } elseif ($this->_rightAddon instanceof Element) {
                $html[] = '<div  class="input-group-btn">';
                $html[] = $this->_rightAddon->render();
                $html[] = '</div>';
            } else {
                $html[] = $this->_rightAddon;
            }
        }

        $html[] = $this->renderEnd();
        return implode('', $html);
    }
}