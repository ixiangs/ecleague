<?php
namespace Toy\Web;

class Domain
{

    private $_name = '';
    private $_namespace = '';
    private $_startUrl = '';
    private $_indexUrl = '';
    private $_loginUrl = '';
    private $_default = false;
    private $_authorizationRequired = false;

    public function __construct($name, $namespace,
                                $startUrl, $indexUrl,
                                $loginUrl = '',$authorizationRequired = false,
                                $default = false)
    {
        $this->_name = $name;
        $this->_namespace = $namespace;
        $this->_startUrl = $startUrl;
        $this->_indexUrl = $indexUrl;
        $this->_loginUrl = $loginUrl;
        $this->_authorizationRequired = $authorizationRequired;
        $this->_default = $default;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function getNamespace()
    {
        return $this->_namespace;
    }

    public function getStartUrl()
    {
        return $this->_startUrl;
    }

    public function getIndexUrl()
    {
        return $this->_indexUrl;
    }

    public function getAuthorizationRequired()
    {
        return $this->_authorizationRequired;
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
