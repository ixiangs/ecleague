<?php
namespace Core\Html\Widget;

use Toy\Util\StringUtil;

class LabelColumn extends BaseColumn{

    public function getCellHtml($row, $index){
        $val = StringUtil::substitute($this->getCellText(), $row);
        if(empty($val)){
            $val = $this->getDefaultText();
        }
        $res = $this->getCell()->getStartHtml();
        $res .= $val;
        $res .= $this->getCell()->getEndHtml();
        return $res;
    }

    public function getType(){
        return 'label';
    }
}