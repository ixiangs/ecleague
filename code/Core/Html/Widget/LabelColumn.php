<?php
namespace Core\Html\Widget;

class LabelColumn extends TableColumn
{

    public function __construct()
    {
        parent::__construct();
        $this->getCell()->addChild(new Element('span'))
            ->getChild(0)->addBindableAttribute('text');
    }

    public function renderCell($row, $index){
        $label = $this->getCell()->getChild(0)->bindAttribute($row);
        if(empty($label->getAttribute('text'))){
            $label->setAttribute('text', $this->getDefaultText());
        }
        return parent::renderCell($row, $index);
    }
}