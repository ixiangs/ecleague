<?php
namespace Core\Html\Widget;

use Toy\Util\StringUtil;

class CheckboxColumn extends BaseColumn{

    private $_checkbox = null;

    public function __construct(){
        parent::__construct();
        $this->_checkbox = new Element('input');
        $this->_checkbox->setAttribute('type', 'checkbox')->addBindableAttribute('value', 'id', 'name');
    }

    public function getCheckbox(){
        return $this->_checkbox;
    }

    public function renderCell($row, $index){
        $this->_checkbox->bindAttribute($row);
        $res = $this->getCell()->renderBegin();
        $res .= $this->_checkbox->render();
        $res .= $this->getCell()->renderEnd();
        return $res;
    }

    public function getType(){
        return 'checkbox';
    }
}