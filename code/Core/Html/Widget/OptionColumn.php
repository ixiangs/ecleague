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
        if(array_key_exists($op, $this->_options)){
            $res .= $this->_options[$op];
        }else{
            $res .= $this->getDefaultText();
        }
        $res .= $this->getCell()->renderEnd();
        return $res;
    }

    public function getType(){
        return 'option';
    }
}