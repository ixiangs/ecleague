<?php
namespace Toy\Web;

use Toy\Log\Logger;
use Toy\Platform\PathUtil;
use Toy\Html\Helper;
use Toy\Html\Document;

class Template
{

    private static $_blocks = array();
    private static $_data = null;
    private static $_currentContentBlock = null;
    private static $_currentScriptBlock = null;
    protected $applicationContext = null;
    protected $document = null;

    public function __construct($data = array())
    {
        $this->document = Document::singleton();
        $this->applicationContext = Application::$context;
        if (is_null(self::$_data)) {
            self::$_data = array(
                'request' => $this->applicationContext->request,
                'session' => $this->applicationContext->session,
                'localize' => $this->applicationContext->localize,
                'router' => $this->applicationContext->router
            );
        }
        self::$_data = array_merge(self::$_data, $data);
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

    public function beginScript($name)
    {
        ob_start();
        self::$_currentScriptBlock = $name;
        return $this;
    }

    public function endScript()
    {
        $this->document->addScriptBlock(self::$_currentScriptBlock, ob_get_clean());
        return $this;
    }

    protected function includeTemplate($filename, $data = array())
    {
        $tmpl = new self($data);
        return $tmpl->render($filename);
    }

//    public function renderBreadcrumbs()
//    {
//        $result = array('<ol class="breadcrumb">');
//        foreach ($this->document->getBreadcrumbs() as $item) {
//            $result[] = sprintf('<li><a href="%s">%s</a></li>',
//                array_key_exists('url', $item) ? $item['url'] : '#',
//                $item['text']);
//        }
//        $result[] = '</ol>';
//        return implode('', $result);
//    }

    public function renderScriptBlocks()
    {
        $result = array();
        foreach ($this->document->getScriptBlocks() as $script) {
            if (!empty($script)) {
                $result[] = $script;
            }
        }
        return implode("\n", $result);
    }

    public function renderReferenceScripts()
    {
        $result = array();
        foreach ($this->document->getReferenceScripts() as $script) {
            if (!empty($script)) {
                $attributes = array();
                foreach ($script['attributes'] as $name => $value) {
                    $attributes[] = $name . '"' . $value . '"';
                }
                $result[] = '<script src="' . $script['address'] . '" ' . implode(' ', $attributes) . '></script>';
            }
        }
        return implode("\n", $result);
    }

    public function renderReferenceCss()
    {
        $result = array();
        foreach ($this->document->getReferenceCss() as $css) {
            if (!empty($css)) {
                $attributes = array();
                foreach ($css['attributes'] as $name => $value) {
                    $attributes[] = $name . '"' . $value . '"';
                }
                $result[] = '<link href="' . $css['address'] . '" ' . implode(' ', $attributes) . ' rel="stylesheet">';
            }
        }
        return implode("\n", $result);
    }

    public function render($path)
    {
        $allPaths = array();
        $root = Configuration::$templateRoot;
        $lang = Application::$context->request->getBrowserLanguage();
        $domain = Application::$context->router->domain;
        $extensions = Configuration::$templateExtensions;
        if($path[0] != '/'){
            $path = '/'.$path;
        }
        $subPaths = array(
            $domain->getName() . '/' . $lang . $path,
            $domain->getName() . $path,
            $path
        );

        foreach ($extensions as $ex) {
            foreach ($subPaths as $subPath) {
                $file = PathUtil::combines($root, $subPath, $ex);
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

        print_r($allPaths);
        throw new \Exception("Not found template:" . implode(",", $path));
    }

    private static $_helpers = array();

    static public function addHelper($name, $func)
    {
        self::$_helpers[$name] = $func;
    }
}

