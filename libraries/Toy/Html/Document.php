<?php
namespace Toy\Html;

class Document
{
    protected $referenceScripts = array();
    protected $referenceCss = array();
    protected $scriptBlocks = array();
    protected $cssBlocks = array();
    protected $metadatas = array();
    protected $breadcrumbs = array();

    private function __construct()
    {
    }

    public function getMetadatas()
    {
        return $this->metadatas;
    }

    public function addMetadata(array $attributes)
    {
        $this->metadatas[] = $attributes;
    }

    public function getScriptBlocks()
    {
        return $this->scriptBlocks;
    }

    public function addScriptBlock($name, $script)
    {
        $this->scriptBlocks[$name] = $script;
        return $this;
    }

    public function getCssBlocks()
    {
        return $this->cssBlocks;
    }

    public function addCssBlock($name, $script)
    {
        $this->cssBlocks[$name] = $script;
        return $this;
    }

    public function getReferenceScripts()
    {
        return $this->referenceScripts;
    }

    public function addReferenceScript($name, $address, array $attributes = array())
    {
        $this->referenceScripts[$name] = array(
            'address' => $address,
            'attributes' => $attributes
        );
        return $this;
    }

    public function getReferenceCss()
    {
        return $this->referenceCss;
    }

    public function addReferenceCss($name, $address, array $attributes = array())
    {
        $this->referenceCss[$name] = array(
            'address' => $address,
            'attributes' => $attributes
        );
        return $this;
    }

//    public function getBreadcrumbs()
//    {
//        return $this->breadcrumbs;
//    }
//
//    public function setBreadcrumbs($value)
//    {
//        $this->breadcrumbs = $value;
//        return $this;
//    }
//
//    public function addBreadcrumbs($text, $url = '#')
//    {
//        $this->breadcrumbs[] = array('text' => $text, 'url' => $url);
//        return $this;
//    }

    static private $_instance = null;

    static public function singleton()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new static();
        }
        return self::$_instance;
    }
}

