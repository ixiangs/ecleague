<?php
namespace Core\Html\Widget;

class LabelField extends BaseField
{

    public function __construct($label)
    {
        parent::__construct($label);
        $this->_input = new Element('p', array(
           'class'=>'form-control-static'
        ));
    }

    public function getInput(){
        return $this->_input;
    }

    protected function renderInput()
    {
        return $this->_input->render();
    }
}