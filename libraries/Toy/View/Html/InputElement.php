<?php
namespace Toy\View\Html;

abstract class InputElement extends Element
{
    protected $validateRules = array();

    public function getValidateRules()
    {
        return $this->validateRules;
    }

    public function addValidateRule($name, $value, $msg = null)
    {
        $this->validateRules[$name] = array('value' => $value, 'message' => $msg);
        return $this;
    }

    public function renderAttribute()
    {
        foreach ($this->validateRules as $name => $rule) {
            $this->setAttribute('data-validate-' . $name, $rule['value'] === true ? 'true' : $rule['value']);
            if (!empty($rule['message'])) {
                $this->setAttribute('data-validate-' . $name . '-msg', $rule['message']);
            }
        }
        return parent::renderAttribute();
    }
}