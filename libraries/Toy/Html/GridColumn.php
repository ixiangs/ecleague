<?php
namespace Toy\Html;

class GridColumn{

    protected $head = null;
    protected $cell = null;
    protected $foot = null;

    protected $filter = null;

    protected $filterRenderer = null;
    protected $headRenderer = null;
    protected $cellRenderer = null;
    protected $footRenderer = null;

    protected $defaultText = '';

    public function __construct($headText, $headCss = null, $cellCss = null){
        $this->head = new Element('th', array('text'=>$headText, 'class'=>$headCss));
        $this->cell = new Element('td', array('class'=>$headCss));
        $this->foot = new Element('td');
    }

    public function getDefaultText(){
        return $this->defaultText;
    }

    public function setDefaultText($value){
        $this->defaultText = $value;
        return $this;
    }

    public function getHead(){
        return $this->head;
    }

    public function getCell(){
        return $this->cell;
    }

    public function getFoot(){
        return $this->foot;
    }

    public function getHeadRenderer(){
        return $this->headRenderer;
    }

    public function setHeadRenderer($value){
        $this->headRenderer = $value;
        return $this;
    }

    public function getCellRenderer(){
        return $this->cellRenderer;
    }

    public function setCellRenderer($value){
        $this->cellRenderer = $value;
        return $this;
    }

    public function getFootRenderer(){
        return $this->footRenderer;
    }

    public function setFootRenderer($value){
        $this->footRenderer = $value;
        return $this;
    }

    public function getFilter(){
        return $this->filter;
    }

    public function setFilter($value){
        $this->filter = $value;
        return $this;
    }

    public function getFilterRenderer(){
        return $this->filterRenderer;
    }

    public function setFilterRenderer($value){
        $this->filterRenderer = $value;
        return $this;
    }

    public function renderFilter(){
        if(!is_null($this->filterRenderer)){
            return call_user_func($this->filterRenderer, $this);
        }
        if(!is_null($this->filter)){
            return $this->filter->render();
        }
        return '';
    }

    public function renderHead(){
        if(!is_null($this->headRenderer)){
            return call_user_func($this->headRenderer, $this);
        }
        return $this->head->render();
    }

    public function renderFoot(){
        if(!is_null($this->footRenderer)){
            return call_user_func($this->footRenderer, $this);
        }
        return $this->foot->render();
    }

    public function renderCell($row, $index){
        if(!is_null($this->cellRenderer)){
            return call_user_func_array($this->cellRenderer, array($this, $row, $index));
        }
        return $this->cell->render($row);
    }
}