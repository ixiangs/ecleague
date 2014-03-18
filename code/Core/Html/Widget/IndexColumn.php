<?php
namespace Core\Html\Widget;

use Toy\Util\StringUtil;

class IndexColumn extends BaseColumn{

    public function getCellHtml($row, $index){
        $res = $this->getCell()->getStartHtml();
        $res .= $index + 1;
        $res .= $this->getCell()->getEndHtml();
        return $res;
    }

    public function getType(){
        return 'label';
    }
}