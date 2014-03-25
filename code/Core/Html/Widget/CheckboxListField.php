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

    protected function renderInput()
    {
        return $this->_checkboxes->render();
    }
}