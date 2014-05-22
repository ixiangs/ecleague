<?php
namespace Toy\View;

use Toy\Log\Logger;
use Toy\Platform\PathUtil;
use Toy\View\Html\Helper;
use Toy\View\Html\Document;

class Template
{

    private static $_blocks = array();
    private static $_data = array();
    private static $_currentContentBlock = null;
    private static $_currentScriptBlock = null;
    protected $document = null;

    public function __construct($data = array())
    {
        $this->document = Document::singleton();
        if (is_array($data)) {
            self::$_data = array_merge(self::$_data, $data);
        }
    }

    public function __get($name)
    {
        if (array_key_exists($name, self::$_helpers)) {
            return self::$_helpers[$name];
        }
        return $this->get($name);
    }

    public function __call($name, $args)
    {
        if (array_key_exists($name, self::$_helpers)) {
            return call_user_func_array(self::$_helpers[$name], $args);
        }
    }

    public function assign()
    {
        $args = func_get_args();
        $len = func_num_args();
        if ($len == 1 && is_array($args[0])) {
            self::$_data = array_merge(self::$_data, $args);
        } else {
            self::$_data[$args[0]] = $args[1];
        }
        return $this;
    }

    public function get($name, $default = '')
    {
        if (array_key_exists($name, self::$_data)) {
            return self::$_data[$name];
        }

        if ($name == 'html') {
            return Helper::singleton();
        }

        return $default;
    }

    public function escape($name, $default = '')
    {
        if (array_key_exists($name, self::$_data)) {
            $result = urldecode(self::$_data[$name]);
            return $result;
        }

        return $default;
    }

    public function has($name)
    {
        return array_key_exists($name, self::$_data);
    }

    protected function removeBlock($name = 'default')
    {
        if ($this->hasBlock($name)) {
            unset(self::$_blocks[$name]);
        }
        return $this;
    }

    protected function beginBlock($name = 'default')
    {
        ob_start();
        self::$_currentContentBlock = $name;
        return $this;
    }

    protected function endBlock()
    {
        if ($this->hasBlock(self::$_currentContentBlock)) {
            $content = self::$_blocks[self::$_currentContentBlock];
            $content .= ob_get_clean();
            self::$_blocks[self::$_currentContentBlock] = $content;
        } else {
            self::$_blocks[self::$_currentContentBlock] = ob_get_clean();
        }
        self::$_currentContentBlock = null;
        return $this;
    }

    protected function nextBlock($name = 'default')
    {
        $this->endBlock();
        $this->beginBlock($name);
        return $this;
    }

    protected function renderBlock($name = 'default', $default = '')
    {
        foreach (self::$_blocks as $n => $v) {
            if ($name == $n) {
                return $v;
            }
        }
        return $default;
    }

    protected function hasBlock($name)
    {
        return array_key_exists($name, self::$_blocks);
    }

    public function beginScript($name){
        ob_start();
        self::$_currentScriptBlock = $name;
        return $this;
    }

    public function endScript(){
        $this->document->addScriptBlock(self::$_currentScriptBlock, ob_get_clean());
        return $this;
    }

    protected function includeTemplate($filename, $data = array())
    {
        $tmpl = new self($data);
        return $tmpl->render($filename);
    }

    public function renderScriptBlocks(){
        $result = array();
        foreach($this->document->getScriptBlocks() as $script){
            $result[] = $script;
        }
        return implode("\n", $result);
    }

    public function renderReferenceScripts(){
        $result = array();
        foreach($this->document->getReferenceScripts() as $script){
            $attributes = array();
            foreach($script['attributes'] as $name=>$value){
                $attributes[] = $name.'"'.$value.'"';
            }
            $result[] = '<script src="'.$script['address'].'" '.implode(' ', $attributes).'></script>';
        }
        return implode("\n", $result);
    }

    public function renderReferenceCss(){
        $result = array();
        foreach($this->document->getReferenceCss() as $css){
            $attributes = array();
            foreach($css['attributes'] as $name=>$value){
                $attributes[] = $name.'"'.$value.'"';
            }
            $result[] = '<link href="'.$css['address'].'" '.implode(' ', $attributes).' ref="stylesheet">';
        }
    }

    public function render($path)
    {
        $allPaths = array();
        $root = Configuration::$templateRoot;
        $dirs = Configuration::$templateDirectories;
        $extensions = Configuration::$templateExtensions;
        $subPaths = is_array($path) ? $path : array($path);
        foreach ($dirs as $dir) {
            foreach ($extensions as $ex) {
                foreach ($subPaths as $subPath) {
                    $file = PathUtil::combines($root, $dir, $subPath, $ex);
                    $allPaths[] = $file;
                    if (file_exists($file)) {
                        if (Configuration::$trace) {
                            Logger::singleton()->v($file, 'template');
                        }
                        ob_start();
                        include $file;
                        return ob_get_clean();
                    }
                }
            }
        }

        print_r($allPaths);
        throw new \Exception("Not found template:" . implode(",", $path));
    }

    private static $_helpers = array();

    static public function addHelper($name, $func)
    {
        self::$_helpers[$name] = $func;
    }
}

