<?php
namespace Core\Html\Widget;

class CheckboxesField extends BaseField
{

    private $_options = array();

    public function __construct($label, $id, $name, $value = null)
    {
        parent::__construct($label, $id, $name, $value);
    }

    public function setOptions($value)
    {
        $this->_options  = $value;
        return $this;
    }

    public function getOptions()
    {
        return $this->_options ;
    }

    protected function renderInput()
    {
        $vattrs = [];
        foreach ($this->getValidateRules() as $n => $v) {
            $vattrs[] = array('data-validate-' . $n.'="'.$v.'"');
        }
        $vattrs = implode(' ', $vattrs);

        $html = array();
        foreach ($this->_options as $option => $text) {
            if (is_array($this->getValue()) && in_array($option, $this->getValue())) {
                $html[] = sprintf('<label class="checkbox-inline"><input type="checkbox" name="%s" value="%s" checked="true" %s/>%s</label>', $this->getInputName(), $option, $vattrs, $text);
            } else {
                $html[] = sprintf('<label class="checkbox-inline"><input type="checkbox" name="%s" value="%s" %s/>%s</label>', $this->getInputName(), $option, $vattrs, $text);
            }
        }
        return implode('', $html);
    }
}