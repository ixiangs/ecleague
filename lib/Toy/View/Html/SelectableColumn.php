<?php
namespace Toy\View\Html;

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
        return parent::renderCell($row, $index);
    }
}