<?php
namespace Core\Html\Widget;

abstract class BaseColumn{

    private $_head = null;
    private $_cell = null;
    private $_footer = null;
//    private $_headText = null;
//    private $_cellText = null;
//    private $_footerText = null;
    private $_defaultText = '';

    public function __construct(){
        $this->_head = new Element('th');
        $this->_cell = new Element('td');
        $this->_footer = new Element('td');
    }

//    public function getHeadText(){
//        return $this->_headText;
//    }
//
//    public function setHeadText($value){
//        $this->_headText = $value;
//        return $this;
//    }
//
//    public function getCellText(){
//        return $this->_cellText;
//    }
//
//    public function setCellText($value){
//        $this->_cellText = $value;
//        return $this;
//    }

    public function getDefaultText(){
        return $this->_defaultText;
    }

    public function setDefaultText($value){
        $this->_defaultText = $value;
        return $this;
    }

//    public function getFooterText(){
//        return $this->_footerText;
//    }
//
//    public function setFooteText($value){
//        $this->_footerText = $value;
//        return $this;
//    }

    public function getHead(){
        return $this->_head;
    }

    public function getCell(){
        return $this->_cell;
    }

    public function getFooter(){
        return $this->_footer;
    }

//    public function setHeadId($value){
//        $this->_head->setId($value);
//        return $this;
//    }
//
//    public function setCellId($value){
//        $this->_cell->setId($value);
//        return $this;
//    }
//
//    public function setFooterId($value){
//        $this->_footer->setId($value);
//        return $this;
//    }
//
//    public function setHeadCss($value){
//        $this->_head->setCss($value);
//        return $this;
//    }
//
//    public function setCellCss($value){
//        $this->_cell->setCss($value);
//        return $this;
//    }
//
//    public function setFooterCss($value){
//        $this->_footer->setCss($value);
//        return $this;
//    }

//    public function setHeadAttributes($value){
//        $this->_head->setAttributes($value);
//        return $this;
//    }
//
//    public function setCellAttributes($value){
//        $this->_cell->setAttributes($value);
//        return $this;
//    }
//
//    public function setFooterAttributes($value){
//        $this->_footer->setAttributes($value);
//        return $this;
//    }

    public function renderHead(){
        $res = $this->_head->renderBegin();
        $res .= $this->_headText;
        $res .= $this->_head->renderEnd();
        return $res;
    }

    public function renderFooter(){
        return '';
    }

    abstract public function renderCell($row, $index);
    abstract public function getType();
}