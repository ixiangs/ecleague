<?php
namespace Core\Html\Widget;

class TreeSelectField extends SelectField
{
    public function __construct($label)
    {
        parent::__construct($label);
        $this->input = new TreeSelect(array('class'=>'form-control'));
    }
}