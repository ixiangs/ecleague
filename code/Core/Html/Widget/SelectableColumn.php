<?php
namespace Core\Html\Widget;

class SelectableColumn extends GridColumn{

    public function __construct(){
        parent::__construct();
        $checkbox = new Element('input',array('type'=>'checkbox', 'class'=>'selectable'));
        $checkbox->addBindableAttribute('value', 'id', 'name');
        $this->getCell()->addChild($checkbox);
        $this->getHead()->addChild(new Element('input', array('type'=>'checkbox', 'class'=>'selectable-head')));
    }

    public function renderCell($row, $index){
        $this->getCell()->getChild(0)->bindAttribute($row);
//        $res = $this->getCell()->renderBegin();
//        $res .= $this->_checkbox->render();
//        $res .= $this->getCell()->renderEnd();
        return parent::renderCell($row, $index);
    }
}