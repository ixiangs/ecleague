<?php
namespace Toy\View\Html;

class InputField extends FormField
{
//    private $_input = null;

    public function __construct($type, $label)
    {
        parent::__construct($label);
        $this->input = new Element('input', array(
            'type' => $type,
            'class' => 'form-control'
        ));
    }

//    public function getInput()
//    {
//        return $this->_input;
//    }

    protected function renderInput()
    {
        foreach ($this->getValidateRules() as $n => $v) {
            if (is_array($v)) {
                $this->input->setAttribute('data-validate-' . $n, $v['value'] === true ? 'true' : $v['value']);
                $this->input->setAttribute('data-validate-' . $n . '-msg', $v['message']);
            } else {
                $this->input->setAttribute('data-validate-' . $n, $v === true ? 'true' : $v);
            }
        }

        return $this->input->render();
    }
}