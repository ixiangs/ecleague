<?php
namespace Core\Html\Widget;

use Toy\Util\StringUtil;

class CheckboxColumn extends BaseColumn{

    private $_checkboxId = null;
    private $_checkboxName = null;
    private $_checkboxValue = null;
    private $_checkboxAttrs = null;

    public function getCheckboxId(){
        return $this->_checkboxId;
    }

    public function setCheckboxId($value){
        $this->_checkboxId = $value;
        return $this;
    }

    public function getCheckboxName(){
        return $this->_checkboxId;
    }

    public function setCheckboxName($value){
        $this->_checkboxName = $value;
        return $this;
    }

    public function getCheckboxValue(){
        return $this->_checkboxValue;
    }

    public function setCheckboxValue($value){
        $this->_checkboxValue = $value;
        return $this;
    }

    public function getCheckboxAttributes(){
        return $this->_checkboxAttrs;
    }

    public function setCheckboxAttributes($value){
        $this->_checkboxAttrs = $value;
        return $this;
    }

    public function renderCell($row, $index){
        $el = new Element('input');
        $el->addAttribute('type', 'checkbox');
        if(!is_null($this->_checkboxId)){
            $el->setId(StringUtil::substitute($this->_checkboxId, $row));
        }
        if(!is_null($this->_checkboxName)){
            $el->setName(StringUtil::substitute($this->_checkboxName, $row));
        }
        if(!is_null($this->_checkboxValue)){
            $el->addAttribute('value', StringUtil::substitute($this->_checkboxValue, $row));
        }
        if(!is_null($this->_checkboxAttrs)){
            $el->setAttributes($this->_checkboxAttrs);
        }
        $res = $this->getCell()->renderBegin();
        $res .= $el->render();
        $res .= $this->getCell()->renderEnd();
        return $res;
    }

    public function getType(){
        return 'link';
    }
}