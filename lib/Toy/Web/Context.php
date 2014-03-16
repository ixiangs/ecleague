<?php
namespace Toy\Web;

class Context {

	public $request = null;
    public $response = null;
    public $session = null;
    public $router = null;
    public $handler = null;
    public $renderer = null;
    public $result = null;
    public $items = array();

	public function __construct() {}

//    public function getItem($name){
//        return $this->_items[$name];
//    }
//
//    public function __set($name, $value){
//        $this->_items[$name] = $value;
//    }
}
