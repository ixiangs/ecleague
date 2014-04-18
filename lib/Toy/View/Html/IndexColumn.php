<?php
namespace Toy\View\Html;

class IndexColumn extends LabelColumn{

    public function renderCell($row, $index){
        $this->getCell()->getChild(0)->setAttribute('text', $index + 1);
        return parent::renderCell($row, $index);
//        $res = $this->getCell()->renderBegin();
//        $res .= $this->getLabel()->render();
//        $res .= $this->getCell()->renderEnd();
//        return $res;
    }
}