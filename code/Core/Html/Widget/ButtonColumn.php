<?php
namespace Core\Html\Widget;

class ButtonColumn extends TableColumn
{

    public function __construct()
    {
        parent::__construct();
        $button = new Element('button');
        $button->setAttribute(array('type' => 'button', 'class' => 'btn btn-default'))
            ->addBindableAttribute('onclick', 'text');
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