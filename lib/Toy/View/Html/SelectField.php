<?php
namespace Toy\View\Html;

class SelectField extends FormField
{

    public function __construct($label)
    {
        parent::__construct($label);
        $this->input = new Select(array('class'=>'form-control'));
    }

    protected function renderInput()
    {
        foreach ($this->getValidateRules() as $n => $v) {
            if(is_array($v)){
                $this->input->setAttribute('data-validate-' . $n, $v['value'] === true? 'true': $v['value']);
                $this->input->setAttribute('data-validate-'.$n.'-msg', $v['message']);
            }else{
                $this->input->setAttribute('data-validate-' . $n, $v === true? 'true': $v);
            }
        }

        return $this->input->render();
    }
}