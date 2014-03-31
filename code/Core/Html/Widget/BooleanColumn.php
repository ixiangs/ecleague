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
        $class = $this->getCell()->getAttribute('class');
        $res = $this->getCell()->setAttribute('class', $val? 'true '.$class:'false '.$class)->renderBegin();
        $res .= $val? '<i class="fa fa-check-circle fa-2x"></i>': '<i class="fa fa-times-circle fa-2x"></i>';
        $res .= $this->getCell()->renderEnd();
        $this->getCell()->setAttribute('class', $class);
        return $res;
    }

    public function getType(){
        return 'boolean';
    }
}