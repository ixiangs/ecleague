<?php
namespace Core\Html\Widget;

use Toy\Util\StringUtil;

class LabelColumn extends BaseColumn{

    public function renderCell($row, $index){
        $val = StringUtil::substitute($this->getCellText(), $row);
        if(empty($val)){
            $val = $this->getDefaultText();
        }
        $res = $this->getCell()->renderBegin();
        $res .= $val;
        $res .= $this->getCell()->renderEnd();
        return $res;
    }

    public function getType(){
        return 'label';
    }
}