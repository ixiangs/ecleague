<?php
namespace Toy\View\Html;

class CheckboxColumn extends GridColumn
{

    public function __construct()
    {
        parent::__construct();
        $checkbox = new Element('input');
        $checkbox->setAttribute('type', 'checkbox')->addBindableAttribute('value', 'id', 'name');
        $this->getCell()->addChild($checkbox);
    }

    public function renderCell($row, $index)
    {
        $this->getCell()->getChild(0)->bindAttribute($row);
        return parent::renderCell($row, $index);
    }
}