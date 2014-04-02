<?php
namespace Core\Html\Widget;

use Toy\Util\StringUtil;

class BooleanColumn extends BaseColumn{

    private $_fieldName = '';
    private $_trueText = '';
    private $_falseText = '';

    public function __construct(){
        parent::__construct();
    }

    public function setFieldName($value){
        $this->_fieldName = $value;
        return $this;
    }

    public function getFieldName(){
        return $this->_fieldName;
    }

    public function setTrueText($value){
        $this->_trueText = $value;
        return $this;
    }

    public function getTrueText(){
        return $this->_trueText;
    }

    public function setFalseText($value){
        $this->_falseText = $value;
        return $this;
    }

    public function getFalseText(){
        return $this->_falseText;
    }

    public function renderCell($row, $index){
//        $val = StringUtil::substitute($this->_value, $row);
//        $class = $this->getCell()->getAttribute('class');
//        $res = $this->getCell()->setAttribute('class', $row[$this->_fieldName]? 'bg-primary '.$class:'bg-danger '.$class)->renderBegin();
        $res = $this->getCell()->renderBegin();
        $res .= $row[$this->_fieldName]? '<span class="text-success">'.$this->_trueText.'<span>': '<span class="text-danger">'.$this->_falseText.'<span>';
        $res .= $this->getCell()->renderEnd();
//        $this->getCell()->setAttribute('class', $class);
        return $res;
    }

    public function getType(){
        return 'boolean';
    }
}