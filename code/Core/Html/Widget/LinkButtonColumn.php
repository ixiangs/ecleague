<?php
namespace Core\Html\Widget;

class LinkButtonColumn extends BaseColumn{

    private $_button = null;

    public function __construct(){
        parent::__construct();
        $this->_button = new Element('button');
        $this->_button->setAttribute(array('type'=>'button', 'class'=>'btn btn-link'))
            ->addBindableAttribute('onclick', 'text');
    }

    public function getButton(){
        return $this->_button;
    }

    public function renderCell($row, $index){
        $this->_button->bindAttribute($row);
        if(empty($this->_button->getAttribute('text'))){
            $this->_button->setAttribute('text', $this->getDefaultText());
        }
        $res = $this->getCell()->renderBegin();
        $res .= $this->_button->render();
        $res .= $this->getCell()->renderEnd();
        return $res;
    }

    public function getType(){
        return 'button';
    }
}