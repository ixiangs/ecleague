<?php
namespace Core\Html\Widget;

class SelectField extends BaseField
{

    private $_select = null;

    public function __construct($label)
    {
        parent::__construct($label);
        $this->_select = new Select(array('class'=>'form-control'));
    }

    public function getSelect(){
        return $this->_select;
    }

    protected function renderInput()
    {
        return $this->_select->render();
    }
}