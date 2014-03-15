<?php
namespace Toys\Web;

class Result{

    private $_context = null;
    private $_type = null;
    private $_parameters = null;

    public function __construct($type, $parameters = null){
        $this->_context = Application::singleton()->getContext();
        $this->_type = $type;
        $this->_parameters = $parameters;
    }

    public function __get($name){
        return $this->_parameters[$name];
    }

    public function __set($name, $value){
        $this->_parameters[$name] = $value;
    }

    public function getContext(){
        return $this->_context;
    }

    public function getParameters(){
        return $this->_parameters;
    }

    public function getType(){
        return $this->_type;
    }

    public static function textResult($content){
        return new self('text', $content);
    }

    public static function templateResult($path, $data){
        return new self('template', array('path'=>$path, 'data'=>$data));
    }

    public static function downloadResult($filename, $content){
        return new self('download', array('filename'=>$filename, 'content'=>$content));
    }

    public static function jsonResult($data){
        return new self('json', array('data'=>$data));
    }

    public static function redirectResult($url, $status = 302){
        return new self('redirect', array('url'=>$url, 'status'=>$status));
    }
}
