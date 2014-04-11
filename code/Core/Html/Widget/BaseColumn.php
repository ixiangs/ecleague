<?php
namespace Core\Html\Widget;

abstract class BaseColumn{

    protected $head = null;
    protected $cell = null;
    protected $footer = null;
    protected $headRenderer = null;
    protected $cellRenderer = null;
    protected $footerRenderer = null;
    protected $defaultText = '';

    public function __construct(){
        $this->head = new Element('th');
        $this->cell = new Element('td');
        $this->footer = new Element('td');
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

    public function getFooter(){
        return $this->footer;
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

    public function getFooterRenderer(){
        return $this->footerRenderer;
    }

    public function setFooterRenderer($value){
        $this->footerRenderer = $value;
        return $this;
    }

    public function renderHead(){
        if(!is_null($this->headRenderer)){
            return $this->headRenderer($this);
        }
        return $this->head->render();
    }

    public function renderFooter(){
        if(!is_null($this->footerRenderer)){
            return $this->footerRenderer($this);
        }
        return '';
    }

    abstract public function renderCell($row, $index);
    abstract public function getType();
}