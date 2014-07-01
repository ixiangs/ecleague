<?php
namespace Toy\View\Html;

class ButtonGroup extends Element
{

    public function __construct()
    {
        parent::__construct('div', array('class' => "btn-group"));
    }

    protected function renderChildren()
    {
        $res = '';
        foreach ($this->children as $child) {
            $res .= $child->render();
        }
        return $res;
    }
}