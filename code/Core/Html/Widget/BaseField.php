<?php
namespace Core\Html\Widget;

abstract class BaseField extends Element
{
    private $_label = null;
    private $_validateRules = array();
    private $_leftAddon = null;
    private $_rightAddon = null;
    private $_description = null;

    public function __construct($label)
    {
        parent::__construct('div', array(
            'class' => 'form-group'
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

    public function addValidateRule($name, $value, $msg = null)
    {
        if(!is_null($msg)){
            $this->_validateRules[$name] = array('value'=>$value, 'message'=>$msg);
        }else{
            $this->_validateRules[$name] = $value;
        }

        return $this;
    }

    public function getLeftAddon()
    {
        return $this->_leftAddon;
    }

    public function setLeftAddon($value)
    {
        $this->_leftAddon = $value;
        return $this;
    }

    public function getRightAddon()
    {
        return $this->_rightAddon;
    }

    public function setRightAddon($value)
    {
        $this->_rightAddon = $value;
        return $this;
    }

    public function getDescription()
    {
        return $this->_description;
    }

    public function setDescription($value)
    {
        $this->_description = $value;
        return $this;
    }

    public function render()
    {
        $html = array($this->renderBegin());
        $html[] = '<label class="col-sm-1 control-label">' . $this->_label . '</label>';
        $html[] = '<div class="col-sm-9">';
        if (!is_null($this->_leftAddon) || !is_null($this->_rightAddon)){
            $html[] = '<div class="input-group">';
            if (!is_null($this->_leftAddon)) {
                $html[] = '<span class="input-group-addon">' . $this->_leftAddon . '</span>';
            }
            $html[] = $this->renderInput();
            if (!is_null($this->_rightAddon)) {
                $html[] = '<span class="input-group-addon">' . $this->_rightAddon . '</span>';
            }
            $html[] = '</div>';
        } else {
            $html[] = $this->renderInput();
        }
        $html[] = '</div>';
        $html[] = $this->renderEnd();

        return implode('', $html);
    }

    abstract protected function renderInput();
}