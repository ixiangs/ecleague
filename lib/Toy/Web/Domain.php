<?php
namespace Toy\Web;

class Domain
{

    private $_name = '';
    private $_startUrl = '';
    private $_indexUrl = '';
    private $_loginUrl = '';
    private $_default = false;

    public function __construct($name, $startUrl, $indexUrl, $loginUrl = '', $default = false)
    {
        $this->_name = $name;
        $this->_startUrl = $startUrl;
        $this->_indexUrl = $indexUrl;
        $this->_loginUrl = $loginUrl;
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

    public function getIndexUrl()
    {
        return $this->_indexUrl;
    }

    public function getLoginUrl()
    {
        return $this->_loginUrl;
    }

    public function getDefault()
    {
        return $this->_default;
    }
}
