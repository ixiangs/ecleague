<?php
namespace Core\Html\Widget;

class InputGroupField extends BaseField
{
    private $_inputGroup = null;

    public function __construct($type, $label)
    {
        parent::__construct($label);
        $this->_inputGroup = new InputGroup($type);
    }

    public function getInputGroup()
    {
        return $this->_inputGroup;
    }

    protected function renderInput()
    {
        foreach ($this->getValidateRules() as $n => $v) {
            if (is_array($v)) {
                $this->_inputGroup->getInput()
                    ->setAttribute('data-validate-' . $n, $v['value'] === true ? 'true' : $v['value'])
                    ->setAttribute('data-validate-' . $n . '-msg', $v['message']);
            } else {
                $this->_inputGroup->getInput()->setAttribute('data-validate-' . $n, $v === true ? 'true' : $v);
            }
        }

        return $this->_inputGroup->render();
    }
}