<?php
namespace Core\Html\Widget;

class InputField extends BaseField
{

    private $_type = 'text';

    public function __construct($type, $label, $id, $name, $value = null)
    {
        parent::__construct($label, $id, $name, $value);
        $this->_type = $type;
    }

    public function setType($value)
    {
        $this->_type = $value;
        return $this;
    }

    public function getType()
    {
        return $this->_type;
    }

    protected function renderInput()
    {
        $input = new Element('input');
        $input->setAttributes($this->getInputAttributes())
            ->setCss('form-control')
            ->addAttribute('type', $this->_type)
            ->addAttribute('value', $this->getValue())
            ->setName($this->getInputName())
            ->setId($this->getInputId());

        foreach ($this->getValidateRules() as $n => $v) {
            $input->addAttribute('data-validate-' . $n, $v === true? 'true': $v);
        }

        return $input->render();
    }
}