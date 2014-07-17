<?php
namespace Toy\Html;

class FileInput extends InputElement
{
    private $_uploadUrl = null;
    private $_maxCount = 1;

    public function __construct($attrs = array())
    {
        $attrs['type'] = 'hidden';
        parent::__construct('input', $attrs);
    }

    public function getUploadUrl(){
        return $this->_uploadUrl;
    }

    public function setUploadUrl($value){
        $this->_uploadUrl = $value;
        return $this;
    }

    public function getMaxCount(){
        return $this->_maxCount;
    }

    public function setMaxCount($value){
        $this->_maxCount = $value;
        return $this;
    }

    public function render($data = array())
    {
        if (!is_null($this->renderer)) {
            return call_user_func($this->renderer, $this);
        }

        $value = $this->getAttribute('value');
        $result = '<div id="icon_container" class="file-container" '
                . 'data-max-count="'.$this->_maxCount.'">';
        $result .= '</div><div class="clearfix"></div>';
        $result .= '<iframe src="'.$this->_uploadUrl.'" style="height:40px;width:100%;border:none;padding:0;margin:0"></iframe>';
        $result .= '<input '.$this->renderAttribute($data).'/>';
        return $result;
    }
}