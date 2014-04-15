<?php
namespace Core\Html\Widget;

class CheckboxColumn extends TableColumn
{

    public function __construct()
    {
        parent::__construct();
        $checkbox = new Element('input');
        $checkbox->setAttribute('type', 'checkbox')->addBindableAttribute('value', 'id', 'name');
        $this->getCell()->addChild($checkbox);
    }

    public function renderCell($row, $index)
    {
        $this->getCell()->getChild(0)->bindAttribute($row);
//        $res = $this->getCell()->renderBegin();
//        $res .= $this->_checkbox->render();
//        $res .= $this->getCell()->renderEnd();
//        return $res;

        return parent::renderCell($row, $index);
    }
}