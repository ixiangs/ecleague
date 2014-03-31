<?php
namespace Core\Html\Widget;

class CheckboxListField extends BaseField
{
    private $_checkboxes = null;

    public function __construct($label)
    {
        parent::__construct($label);
        $this->_checkboxes = new CheckboxList();
    }

    public function getCheckboxes()
    {
        return $this->_checkboxes;
    }

    protected function renderInput()
    {
        foreach($this->getValidateRules() as $k=>$v){
            $this->_checkboxes->setAttribute($k, $v);
        }
        return $this->_checkboxes->render();
    }
}