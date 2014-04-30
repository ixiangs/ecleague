<?php
namespace Toy\View\Html;

class DropdownButton extends Element
{

    private $button = null;

    public function __construct($button, $attrs = array('class' => "btn-group"))
    {
        $this->button = $button;
        parent::__construct('div', $attrs);
    }

    public function getButton()
    {
        return $this->button;
    }

    public function setButton($value)
    {
        $this->button = $value;
        return $this;
    }

    public function renderInner()
    {
        $res = $this->button->render();
        $res .= '<button type="button" class="'.$this->button->getAttribute('class').' dropdown-toggle" data-toggle="dropdown"><span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>';
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