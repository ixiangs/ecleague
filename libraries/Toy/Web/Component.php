<?php
namespace Toy\Web;

class Component
{

    private $_settings = null;
    private $_id = '';
    private $_code = '';
    private $_name = '';
    private $_version = '';
    private $_author = '';
    private $_website = '';
    private $_breadcrumbs = null;
    private $_listeners = null;

    public function __construct($settings)
    {
        $this->_settings = $settings;
        $this->_id = $this->_settings['id'];
        $this->_code = $this->_settings['code'];
        $this->_name = $this->_settings['name'];
        $this->_author = $this->_settings['author'];
        $this->_version = $this->_settings['version'];
        $this->_website = $this->_settings['website'];
        $this->_listeners = array_key_exists('listeners', $settings) ? $settings['listeners'] : null;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function getCode()
    {
        return $this->_code;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function getAuthor()
    {
        return $this->_author;
    }

    public function getVersion()
    {
        return $this->_version;
    }

    public function getWebsite()
    {
        return $this->_website;
    }

    public function getSetting($name){
        if(array_key_exists($name, $this->_settings)){
            return $this->_settings[$name];
        }
        return null;
    }
}
