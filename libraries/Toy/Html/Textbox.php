<?php
namespace Toy\Html;

class Textbox extends InputElement
{
    public function __construct($type)
    {
        parent::__construct('input', array(
            'type' => $type,
            'class' => 'form-control'
        ));
    }

//    public function render($data = array())
//    {
//        if (!is_null($this->renderer)) {
//            return call_user_func($this->renderer, $this);
//        }
//
//        $res = '<input type="text" ' . $this->renderAttribute($data) . '/>';
//        return $res;
//    }
}