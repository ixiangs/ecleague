<?php
namespace Toy\View\Html;

class LinkColumn extends GridColumn
{

    public function __construct()
    {
        parent::__construct();
        $link = new Element('a', array('class' => 'btn btn-link'));
        $link->addBindableAttribute('href', 'text', 'onclick');
        $this->getCell()->addChild($link);
    }

    public function renderCell($row, $index)
    {
        if(!is_null($this->cellRenderer)){
            return call_user_func_array($this->cellRenderer, array($this, $row, $index));
        }

        $link = $this->getCell()->getChild(0);
        $link->bindAttribute($row);
        if (empty($link->getAttribute('text'))) {
            $link->setAttribute('text', $this->getDefaultText());
        }

        return $this->cell->render();
    }
}