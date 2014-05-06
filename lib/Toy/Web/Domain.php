<?php
namespace Toy\Web;

class Domain
{

    private $_name = '';
    private $_startUrl = '';
    private $_loginUrl = '';
    private $_namespace = '';
    private $_default = false;

    public function __construct($name, $namespace, $startUrl, $loginUrl = '', $default = false)
    {
        $this->_name = $name;
        $this->_startUrl = $startUrl;
        $this->_loginUrl = $loginUrl;
        $this->_namespace = $namespace;
        $this->_default = $default;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function getStartUrl()
    {
        return $this->_startUrl;
    }

    public function getLoginUrl(){
        return $this->_loginUrl;
    }

    public function getNamespace()
    {
        return $this->_namespace;
    }

    public function getDefault()
    {
        return $this->_default;
    }
}