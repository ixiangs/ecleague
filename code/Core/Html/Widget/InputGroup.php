<?php
namespace Core\Html\Widget;

class InputGroup extends Element
{

    private $_leftAddon = null;
    private $_rightAddon = null;
    private $_input = null;

    public function __construct($type = 'text')
    {
        parent::__construct('div', array(
            'class' => 'input-group'
        ));
        $this->_input = new Element('input', array(
            'type' => $type,
            'class' => 'form-control'
        ));
    }

    public function getLeftAddon()
    {
        return $this->_leftAddon;
    }

    public function setLeftAddon($value)
    {
        $this->_leftAddon = $value;
        return $this;
    }

    public function getRightAddon()
    {
        return $this->_rightAddon;
    }

    public function setRightAddon($value)
    {
        $this->_rightAddon = $value;
        return $this;
    }

    public function getInput()
    {
        return $this->_input;
    }

    protected function renderChildren()
    {
        $res = '';
        if (!is_null($this->_leftAddon)) {
            if($this->_leftAddon instanceof ButtonGroup){
                $res .= '<div class="input-group-btn">';
                $res .= $this->_leftAddon->render();
                $res .= '</div>';
            }elseif ($this->_leftAddon instanceof Element) {
                $res .= '<span  class="input-group-btn">';
                $res .= $this->_leftAddon->render();
                $res .= '</span >';
            } else {
                $res .= $this->_leftAddon;
            }
        }
        $res .= $this->_input->render();
        if (!is_null($this->_rightAddon)) {
            if($this->_rightAddon instanceof ButtonGroup){
                $res .= '<div class="input-group-btn">';
                $res .= $this->_leftAddon->render();
                $res .= '</div>';
            }elseif ($this->_rightAddon instanceof Element) {
                $res .= '<span  class="input-group-btn">';
                $res .= $this->_rightAddon->render();
                $res .= '</span >';
            } else {
                $res .= $this->_rightAddon;
            }
        }
        return $res;
    }
}