<?php
namespace Toy\View\Html;

class LabelColumn extends GridColumn
{

    protected $emptyText = null;

    public function __construct()
    {
        parent::__construct();
        $span = new Element('span');
        $this->getCell()->appendChild($span->addBindableAttribute('text'));
    }

    public function getEmptyText(){
        return $this->emptyText;
    }

    public function setEmptyText($value){
        $this->emptyText = $value;
        return $this;
    }

    public function renderCell($row, $index)
    {
        if (!is_null($this->cellRenderer)) {
            return call_user_func_array($this->cellRenderer, array($this, $row, $index));
        }

        $span = $this->getCell()->getChild(0)->bindAttribute($row);
        $attributes = $span->getBoundAttribute();
        if(empty($attributes['text'])){
            $attributes['text'] = $this->emptyText;
            $span->setBoundAttribute($attributes);
        }
        return $this->cell->render();
    }
}