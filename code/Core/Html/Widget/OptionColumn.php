<?php
namespace Core\Html\Widget;

use Toy\Util\StringUtil;

class OptionColumn extends LabelColumn{

    private $_options = array();

    public function setOptions($value){
        $this->_options = $value;
        return $this;
    }

    public function renderCell($row, $index){
        $st = $this->getLabel()->getAttribute('text');
        $op = StringUtil::substitute($st, $row);
        if(array_key_exists($op, $this->_options)){
            $this->getLabel()->setAttribute('text', $this->_options[$op]);
        }else{
            $this->getLabel()->setAttribute('text', $this->getDefaultText());
        }
        $res = parent::renderCell($row, $index);
        $this->getLabel()->setAttribute('text', $st);
        return $res;
    }

    public function getType(){
        return 'option';
    }
}