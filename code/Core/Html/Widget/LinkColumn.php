<?php
namespace Core\Html\Widget;

use Toy\Util\StringUtil;

class LinkColumn extends BaseColumn{

    private $_link = null;

    public function __construct(){
        parent::__construct();
        $this->_link = new Element('a', array('class'=>'btn btn-link'));
        $this->_link->addBindableAttribute('href', 'text', 'onclick');
    }

    public function getLink(){
        return $this->_link;
    }

    public function renderCell($row, $index){
        $this->_link->bindAttribute($row);
        if(empty($this->_link->getAttribute('text'))){
            $this->_link->setAttribute('text', $this->getDefaultText());
        }
        $res = $this->getCell()->renderBegin();
        $res .= $this->_link->render();
        $res .= $this->getCell()->renderEnd();
        return $res;
    }

    public function getType(){
        return 'link';
    }
}