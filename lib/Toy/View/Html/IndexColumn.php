<?php
namespace Toy\View\Html;

class IndexColumn extends LabelColumn{

    public function renderCell($row, $index){
        if (!is_null($this->cellRenderer)) {
            return call_user_func_array($this->cellRenderer, array($this, $row, $index));
        }

        $this->getCell()->getChild(0)->setAttribute('text', $index + 1);
        return $this->cell->render();
//        $res = $this->getCell()->renderBegin();
//        $res .= $this->getLabel()->render();
//        $res .= $this->getCell()->renderEnd();
//        return $res;
    }
}