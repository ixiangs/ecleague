<?php
namespace Toy\Web;

class Component
{

    private $_name = '';
    private $_version = '';
    private $_author = '';
    private $_website = '';
    private $_breadcrumbs = null;
    private $_listeners = null;

    public function __construct($settings)
    {
        $this->_name = $settings['name'];
        $this->_author = $settings['author'];
        $this->_version = $settings['version'];
        $this->_website = $settings['website'];
        $this->_breadcrumbs = array_key_exists('breadcrumbs', $settings) ? $settings['breadcrumbs'] : null;
        $this->_listeners = array_key_exists('listeners', $settings) ? $settings['listeners'] : null;
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

    public function getActionBreadcrumb()
    {
        $result = array();
        if ($this->_breadcrumbs) {
            $router = Application::$context->router;
            $domain = $router->domain->getName();
            if (array_key_exists($domain, $this->_breadcrumbs)) {
                $breadcrumbs = $this->_breadcrumbs[$domain];
                if (array_key_exists($router->component, $breadcrumbs)) {
                    $result[] = $breadcrumbs[$router->component];
                }
                if (array_key_exists($router->controller, $breadcrumbs)) {
                    $result[] = $breadcrumbs[$router->controller];
                }
                if (array_key_exists($router->controller . '_' . $router->action, $breadcrumbs)) {
                    $result[] = $breadcrumbs[$router->controller . '_' . $router->action];
                }
            }
        }
        return $result;
    }

    public function getListeners()
    {
        return $this->_listeners;
    }
}
