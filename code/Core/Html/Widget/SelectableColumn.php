<?php
namespace Core\Html\Widget;

class SelectableColumn extends BaseColumn{

    private $_checkbox = null;

    public function __construct(){
        parent::__construct();
        $this->getHead()->addChild(new Element('input', array('type'=>'checkbox', 'class'=>'selectable-head')));
        $this->_checkbox = new Element('input',array('type'=>'checkbox', 'class'=>'selectable'));
        $this->_checkbox->addBindableAttribute('value', 'id', 'name');
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
        return 'selectable';
    }
}