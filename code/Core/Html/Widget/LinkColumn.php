<?php
namespace Core\Html\Widget;

use Toy\Util\StringUtil;

class LinkColumn extends BaseColumn{

    private $_link = null;
    private $_target = null;

    public function setLink($value){
        $this->_link = $value;
        return $this;
    }

    public function getLink(){
        return $this->_link;
    }

    public function setTarget($value){
        $this->_target = $value;
        return $this;
    }

    public function getTarget(){
        return $this->_target;
    }

    public function renderCell($row, $index){
        $text = StringUtil::substitute($this->getCellText(), $row);
        if(empty($text)){
            $text = $this->getDefaultText();
        }
        $url = StringUtil::substitute(urldecode($this->_link), $row);
        $res = $this->getCell()->renderBegin();
        $res .= '<a href="'.$url.'" target="'.$this->_target.'">'.$text.'</a>';
        $res .= $this->getCell()->renderEnd();
        return $res;
    }

    public function getType(){
        return 'label';
    }
}