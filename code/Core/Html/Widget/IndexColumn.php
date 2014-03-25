<?php
namespace Core\Html\Widget;

class IndexColumn extends LabelColumn{

    public function renderCell($row, $index){
        $this->getLabel()->setAttribute('text', $index + 1);
        $res = $this->getCell()->renderBegin();
        $res .= $this->getLabel()->render();
        $res .= $this->getCell()->renderEnd();
        return $res;
    }

    public function getType(){
        return 'index';
    }
}