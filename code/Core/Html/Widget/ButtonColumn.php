<?php
namespace Core\Html\Widget;

use Toy\Util\StringUtil;

class ButtonColumn extends BaseColumn{

    private $_clickScript = null;

    private $_button = null;

    public function getButton(){
        return $this->_button;
    }

//    public function setClickScript($value){
//        $this->_clickScript = $value;
//        return $this;
//    }
//
//    public function getClickScript(){
//        return $this->_clickScript;
//    }

    public function renderCell($row, $index){
        $text = StringUtil::substitute($this->_button->getAttribute('text'), $row);
        if(empty($text)){
            $text = $this->getDefaultText();
        }
        $res = $this->getCell()->renderBegin();
        $res .= '<a href="javascript:void(0);" onclick="javascript:'.StringUtil::substitute($this->_clickScript, $row).'">'.$text.'</a>';
        $res .= $this->getCell()->renderEnd();
        return $res;
    }

    public function getType(){
        return 'button';
    }
}