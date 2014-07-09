<?php
namespace Toy\Web;

class Component
{

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
        $this->_id = $settings['id'];
        $this->_code = $settings['code'];
        $this->_name = $settings['name'];
        $this->_author = $settings['author'];
        $this->_version = $settings['version'];
        $this->_website = $settings['website'];
        $this->_breadcrumbs = array_key_exists('breadcrumbs', $settings) ? $settings['breadcrumbs'] : null;
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

    public function getBreadcrumbs()
    {
        if ($this->_breadcrumbs) {
            return $this->_breadcrumbs;
//            $router = Application::$context->router;
//            $domain = $router->domain->getName();
//            if (array_key_exists($domain, $this->_breadcrumbs)) {
//                return $this->_breadcrumbs[$domain];
//            }
        }
        return null;
    }

    public function getListeners()
    {
        return $this->_listeners;
    }
}
