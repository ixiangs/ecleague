<?php
namespace Toy\Html;

class Textbox extends InputElement
{

    private $_leftAddon = null;
    private $_rightAddon = null;

    public function __construct($type)
    {
        parent::__construct('input', array(
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

    public function render($data = array())
    {
        if (!is_null($this->renderer)) {
            return call_user_func($this->renderer, $this);
        }

        $res = '';
        if (!is_null($this->_leftAddon)) {
            if ($this->_leftAddon instanceof ButtonGroup) {
                $res .= '<div class="input-group-btn">';
                $res .= $this->_leftAddon->render();
                $res .= '</div>';
            } elseif ($this->_leftAddon instanceof Element) {
                $res .= '<span  class="input-group-btn">';
                $res .= $this->_leftAddon->render();
                $res .= '</span >';
            } else {
                $res .= $this->_leftAddon;
            }
        }
        $res .= '<input type="text" ' . $this->renderAttribute($data) . '/>';
        if (!is_null($this->_rightAddon)) {
            if ($this->_rightAddon instanceof ButtonGroup) {
                $res .= '<div class="input-group-btn">';
                $res .= $this->_leftAddon->render();
                $res .= '</div>';
            } elseif ($this->_rightAddon instanceof Element) {
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