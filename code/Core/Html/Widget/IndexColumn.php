<?php
namespace Core\Html\Widget;

class IndexColumn extends BaseColumn{

    public function renderCell($row, $index){
        $res = $this->getCell()->renderBegin();
        $res .= $index + 1;
        $res .= $this->getCell()->renderEnd();
        return $res;
    }

    public function getType(){
        return 'index';
    }
}