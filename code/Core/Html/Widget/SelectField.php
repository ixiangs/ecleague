<?php
namespace Core\Html\Widget;

class SelectField extends BaseField
{

    private $_caption = null;
    private $_options = array();

    public function __construct($label, $id, $name, $value = null)
    {
        parent::__construct($label, $id, $name, $value);
    }

    public function setCaption($value)
    {
        $this->_caption  = $value;
        return $this;
    }

    public function getCaption()
    {
        return $this->_caption ;
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
        $input = new Element('select');
        $input->setAttributes($this->getInputAttributes())
            ->setCss('form-control');

        foreach ($this->getValidateRules() as $n => $v) {
            $input->addAttribute('data-validate-' . $n, $v);
        }

        $html = array($input->renderBegin());
        if (!empty($caption)) {
            if (is_string($caption)) {
                $html[] = '<option value="">' . $caption . '</option>';
            }
        }

        foreach ($this->_options as $option => $text) {
            if ($this->getValue() == $option) {
                $html[] = "<option value=\"$option\" selected>$text</option>";
            } else {
                $html[] = "<option value=\"$option\">$text</option>";
            }
        }
        $html[] = $input->renderEnd();
        return implode('', $html);
    }
}