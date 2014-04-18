<?php
namespace Toy\View\Html;

class DropdownButton extends Element
{

    private $_label = null;

    public function __construct($label, $attrs = array('class' => "btn-group"))
    {
        $this->_label = $label;
        parent::__construct('div', $attrs);
    }

    public function getLabel()
    {
        return $this->_label;
    }

    public function setLabel($value)
    {
        $this->_label = $value;
        return $this;
    }

    public function renderInner()
    {
        $res = '<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">'
                .$this->_label . '<span class="caret"></span></button>';
        $res .= $this->renderChildren();
        return $res;
    }

    protected function renderChildren()
    {
        $res = '<ul class="dropdown-menu" role="menu">';
        foreach ($this->children as $child) {
            $res .= '<li>'.$child->render().'</li>';
        }
        return $res . '</ul>';
    }
}