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
        $link = $this->getCell()->getChild(0);
        $link->bindAttribute($row);
        if (empty($link->getAttribute('text'))) {
            $link->setAttribute('text', $this->getDefaultText());
        }
        return parent::renderCell($row, $index);
//        $res = $this->getCell()->renderBegin();
//        if (!is_null($this->cellRenderer)) {
//            $res .= call_user_func_array($this->cellRenderer, array($link, $this, $row, $index));
//        } else {
//            $res .= $link->render();
//        }
//        $res .= $this->getCell()->renderEnd();
//        return $res;
    }
}