<?php
namespace Core\Html\Widget;

abstract class BaseField extends Element
{
    protected $label = null;
    protected $input = null;
    protected $validateRules = array();
    protected $description = null;
    protected $horizontalLayout = true;

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

    public function setHorizontalLayout($value)
    {
        $this->horizontalLayout = $value;
        return $this;
    }

    public function getHorizontalLayout()
    {
        return $this->horizontalLayout;
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
            return $this->renderer($this);
        }

        $html = array($this->renderBegin());
        if ($this->horizontalLayout) {
            $html[] = '<label class="col-sm-1 control-label">' . $this->label . '</label>';
            $html[] = '<div class="col-sm-9">';
            $html[] = $this->renderInput();
            $html[] = '</div>';
        } else {
            $html[] = $this->label ? '<label class="col-sm-1 control-label">' . $this->label . '</label>' : '';
            $html[] = $this->renderInput();
        }
        $html[] = $this->renderEnd();

        return implode('', $html);
    }

    abstract protected function renderInput();
}