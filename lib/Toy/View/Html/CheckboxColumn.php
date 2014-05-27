<?php
namespace Toy\View\Html;

class CheckboxColumn extends GridColumn
{

    public function __construct()
    {
        parent::__construct();
        $checkbox = new Element('input');
        $checkbox->setAttribute('type', 'checkbox')->addBindableAttribute('value', 'id', 'name');
        $this->getCell()->appendChild($checkbox);
    }

    public function renderCell($row, $index)
    {
        if (!is_null($this->cellRenderer)) {
            return call_user_func_array($this->cellRenderer, array($this, $row, $index));
        }

        $this->getCell()->getChild(0)->bindAttribute($row);
        return $this->cell->render();
    }
}