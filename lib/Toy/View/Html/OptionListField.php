<?php
namespace Toy\View\Html;

class OptionListField extends FormField
{
    public function __construct($label, $multiple = true)
    {
        parent::__construct($label);
        $this->input = new OptionList();
        $this->input->setMultiple($multiple);
    }

    protected function renderInput()
    {
        foreach($this->getValidateRules() as $k=>$v){
            $this->input->setAttribute($k, $v);
        }
        return $this->input->render();
    }
}