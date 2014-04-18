<?php
namespace Toy\View\Html;

class CheckboxListField extends FormField
{
    public function __construct($label)
    {
        parent::__construct($label);
        $this->input = new CheckboxList();
    }

    protected function renderInput()
    {
        foreach($this->getValidateRules() as $k=>$v){
            $this->input->setAttribute($k, $v);
        }
        return $this->input->render();
    }
}