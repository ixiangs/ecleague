<?php
namespace Core\Html;

class Element{

    private $_tag = null;
    private $_id = null;
    private $_name = null;
    private $_style = null;
    private $_css = null;
    private $_attributes = array();

    public function __construct($tag){
        $this->_tag = $tag;
    }

    public function getTag(){
        return $this->_tag;
    }

    public function setTag($value){
        $this->_tag = $value;
        return $this;
    }

    public function getId(){
        return $this->_id;
    }

    public function setId($value){
        $this->_id = $value;
        return $this;
    }

    public function getName(){
        return $this->_name;
    }

    public function setName($value){
        $this->_name = $value;
        return $this;
    }

    public function getStyle(){
        return $this->_styles;
    }

    public function setStyle($value){
        $this->_id = $value;
        return $this;
    }

    public function getCss(){
        return $this->_css;
    }

    public function setCss($value){
        $this->_css = $value;
        return $this;
    }

    public function getAttributes(){
        return $this->_attributes;
    }

    public function setAttributes($value){
        $this->_attributes = $value;
        return $this;
    }

    public function addAttribute($name, $value){
        $this->_attributes[$name] = $value;
        return $this;
    }

    public function getAttributeHtml(){
        $attrs = $this->_attributes;
        if(!empty($this->_id)){
            $attrs['id'] = $this->_id;
        }
        if(!empty($this->_name)){
            $attrs['name'] = $this->_name;
        }
        if(!empty($this->_style)){
            $attrs['style'] = $this->_style;
        }
        if(!empty($this->_css)){
            $attrs['class'] = $this->_css;
        }

        $arr = array();
        foreach($attrs as $k=>$v){
            $arr[] = $k.'="'.$v.'"';
        }
        return implode(' ', $arr);
    }

    public function getCloseHtml(){
        return '<'.$this->_tag.' '.$this->getAttributeHtml().'/>';
    }

    public function getInnerHtml(){
        return '';
    }

    public function getStartHtml(){
        return '<'.$this->_tag.' '.$this->getAttributeHtml().'>';
    }

    public function getEndHtml(){
        return '</'.$this->_tag.'>';
    }
}
