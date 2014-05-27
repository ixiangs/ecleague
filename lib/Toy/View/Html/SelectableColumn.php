<?php
namespace Toy\View\Html;

class SelectableColumn extends GridColumn
{

    public function __construct()
    {
        parent::__construct();
        $checkbox = new Element('input', array('type' => 'checkbox', 'class' => 'selectable'));
        $checkbox->addBindableAttribute('value', 'id', 'name');
        $this->getCell()->appendChild($checkbox);
        $this->getHead()->appendChild(new Element('input', array('type' => 'checkbox', 'class' => 'selectable-head')));
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