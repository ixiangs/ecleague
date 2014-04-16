<?php
namespace Core\Html\Widget;

class LabelField extends BaseField
{

    public function __construct($label)
    {
        parent::__construct($label);
        $this->input = new Element('p', array(
           'class'=>'form-control-static'
        ));
    }

//    public function getInput(){
//        return $this->input;
//    }

    protected function renderInput()
    {
        return $this->input->render();
    }
}