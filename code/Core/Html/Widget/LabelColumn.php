<?php
namespace Core\Html\Widget;

use Toy\Util\StringUtil;

class LabelColumn extends BaseColumn{

    private $_label = null;

    public function __construct(){
        parent::__construct();
        $this->_label = new Element('span');
        $this->_label->addBindableAttribute('text');
    }

    public function getLabel(){
        return $this->_label;
    }

    public function renderCell($row, $index){
        $this->_label->bindAttribute($row);
        if(empty($this->_label->getAttribute('text'))){
            $this->_label->setAttribute('text', $this->getDefaultText());
        }
        $res = $this->getCell()->renderBegin();
        $res .= $this->_label->render();
        $res .= $this->getCell()->renderEnd();
        return $res;
    }

    public function getType(){
        return 'label';
    }
}