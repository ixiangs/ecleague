<?php
namespace Core\Html\Widget;

use Toy\Util\StringUtil;

class BooleanColumn extends BaseColumn{

    private $_value = '';

    public function __construct(){
        parent::__construct();
    }

    public function setValue($value){
        $this->_value = $value;
        return $this;
    }

    public function getValue(){
        return $this->_value;
    }

    public function renderCell($row, $index){
        $val = StringUtil::substitute($this->_value, $row);
        $res = $this->getCell()->renderBegin();
        $res .= $val? '<i class="fa fa-check"></i>': '<i class="fa fa-check"></i>';
        $res .= $this->getCell()->renderEnd();
        return $res;
    }

    public function getType(){
        return 'boolean';
    }
}