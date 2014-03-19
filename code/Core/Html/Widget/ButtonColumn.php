<?php
namespace Core\Html\Widget;

use Toy\Util\StringUtil;

class ButtonColumn extends BaseColumn{

    private $_clickScript = null;

    public function setClickScript($value){
        $this->_clickScript = $value;
        return $this;
    }

    public function getClickScript(){
        return $this->_clickScript;
    }

    public function renderCell($row, $index){
        $text = StringUtil::substitute($this->getCellText(), $row);
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