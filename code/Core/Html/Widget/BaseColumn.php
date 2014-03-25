<?php
namespace Core\Html\Widget;

abstract class BaseColumn{

    private $_head = null;
    private $_cell = null;
    private $_footer = null;
    private $_defaultText = '';

    public function __construct(){
        $this->_head = new Element('th');
        $this->_cell = new Element('td');
        $this->_footer = new Element('td');
    }

    public function getDefaultText(){
        return $this->_defaultText;
    }

    public function setDefaultText($value){
        $this->_defaultText = $value;
        return $this;
    }

    public function getHead(){
        return $this->_head;
    }

    public function getCell(){
        return $this->_cell;
    }

    public function getFooter(){
        return $this->_footer;
    }

    public function renderHead(){
        return $this->_head->render();
    }

    public function renderFooter(){
        return '';
    }

    abstract public function renderCell($row, $index);
    abstract public function getType();
}