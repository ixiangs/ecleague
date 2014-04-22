<?php
namespace Toy\View\Html;

class LabelColumn extends GridColumn
{

    public function __construct()
    {
        parent::__construct();
        $this->getCell()->addChild(new Element('span'))
            ->getChild(0)->addBindableAttribute('text');
    }

    public function renderCell($row, $index)
    {
        if (!is_null($this->cellRenderer)) {
            return call_user_func_array($this->cellRenderer, array($this, $row, $index));
        }

        $label = $this->getCell()->getChild(0)->bindAttribute($row);
        if (empty($label->getAttribute('text'))) {
            $label->setAttribute('text', $this->getDefaultText());
        }
        return $this->cell->render();
    }
}