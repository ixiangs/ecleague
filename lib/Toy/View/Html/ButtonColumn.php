<?php
namespace Toy\View\Html;

class ButtonColumn extends GridColumn
{

    public function __construct($type = 'button')
    {
        parent::__construct();
        if($type == 'link'){
            $button = new Element('a');
            $button->setAttribute(array('class' => 'btn btn-link'))
                ->addBindableAttribute('onclick', 'text');
        }else{
            $button = new Element('button');
            $button->setAttribute(array('type' => 'button', 'class' => 'btn btn-default'))
                ->addBindableAttribute('onclick', 'text');
        }

        $this->getCell()->addChild($button);
    }

    public function renderCell($row, $index)
    {
        $button = $this->getCell()->getChild(0);
        $button->bindAttribute($row);
        if (empty($button->getAttribute('text'))) {
            $button->setAttribute('text', $this->getDefaultText());
        }
        return parent::renderCell($row, $index);
    }
}