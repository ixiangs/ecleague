<?php
namespace Toy\Html;

class IframeInput extends InputElement
{

    private $_iframeUrl = null;
    private $_iframeClass = null;

    public function __construct(array $attrs = array())
    {
        $attrs['type'] = 'hidden';
        parent::__construct('input', $attrs);
    }

    public function getIframeUrl(){
        return $this->_iframeUrl;
    }

    public function setIframeUrl($value){
        $this->_iframeUrl = $value;
        return $this;
    }

    public function getIframeClass(){
        return $this->_iframeClass;
    }

    public function setIframeClass($value){
        $this->_iframeClass = $value;
        return $this;
    }

    public function render($data = array())
    {
        if (!is_null($this->renderer)) {
            return call_user_func($this->renderer, $this);
        }

        $res = '<iframe src="'.$this->_iframeUrl.'" class="'.$this->_iframeClass.'"></iframe>';
        $res .= '<input '.$this->renderAttribute($data).'/>';
        return $res;
    }
}