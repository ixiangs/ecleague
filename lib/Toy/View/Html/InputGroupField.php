<?php
namespace Toy\View\Html;

class InputGroupField extends FormField
{
//    private $_inputGroup = null;

    public function __construct($type, $label)
    {
        parent::__construct($label);
        $this->_inputGroup = new InputGroup($type);
    }

//    public function getInputGroup()
//    {
//        return $this->_inputGroup;
//    }

    protected function renderInput()
    {
        foreach ($this->getValidateRules() as $n => $v) {
            if (is_array($v)) {
                $this->input->getInput()
                    ->setAttribute('data-validate-' . $n, $v['value'] === true ? 'true' : $v['value'])
                    ->setAttribute('data-validate-' . $n . '-msg', $v['message']);
            } else {
                $this->input->getInput()->setAttribute('data-validate-' . $n, $v === true ? 'true' : $v);
            }
        }

        return $this->input->render();
    }
}