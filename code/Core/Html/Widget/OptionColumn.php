<?php
namespace Core\Html\Widget;

use Toy\Util\StringUtil;

class OptionColumn extends BaseColumn{

    private $_options = array();

    public function setOptions($value){
        $this->_options = $value;
        return $this;
    }

    public function renderCell($row, $index){
        $op = StringUtil::substitute($this->getCellText(), $row);
        $res = $this->getCell()->renderBegin();
        $res .= $this->_options[$op];
        $res .= $this->getCell()->renderEnd();
        return $res;
    }

    public function getType(){
        return 'label';
    }
}