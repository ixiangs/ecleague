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

    public function __get($name){
        if(array_key_exists($name, $this->items)){
            return $this->items[$name];
        }
        return null;
    }

    public function __set($name, $value){
        $this->items[$name] = $value;
    }
}
