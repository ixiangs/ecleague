<?php
namespace Core\Html\Widget;

class CheckboxList extends Element
{
    private $_options = array();

    public function __construct()
    {
        parent::__construct(null);
    }

    public function setOptions($value)
    {
        $this->_options = $value;
        return $this;
    }

    public function getOptions()
    {
        return $this->_options;
    }

    public function renderBegin()
    {
        return '';
    }

    public function renderEnd()
    {
        return '';
    }

    public function renderInner()
    {
        $vattrs = [];
        foreach ($this->getValidateRules() as $n => $v) {
            $vattrs[] = array('data-validate-' . $n . '="' . $v . '"');
        }
        $vattrs = implode(' ', $vattrs);

        list($name, $id) = $this->getAttribute('name', 'id');
        $val = $this->getAttribute('value');

        $html = array();
        foreach ($this->_options as $option => $text) {
            if (is_array($val) && in_array($option, $val)) {
                $html[] = sprintf('<label class="checkbox-inline"><input type="checkbox" name="%s" value="%s" checked="true" %s/>%s</label>', $name, $option, $vattrs, $text);
            } else {
                $html[] = sprintf('<label class="checkbox-inline"><input type="checkbox" name="%s" value="%s" %s/>%s</label>', $name, $option, $vattrs, $text);
            }
        }
        return implode('', $html);
    }
}