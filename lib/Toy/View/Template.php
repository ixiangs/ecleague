<?php
namespace Toy\View;

use Toy\Platform\PathUtil;
use Toy\View\Html\Helper;

class Template
{

    private static $_blocks = array();
    private static $_data = array();
    private static $_currentBlock = NULL;

    public function __construct($data = array())
    {
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
        self::$_currentBlock = $name;
        return $this;
    }

    protected function endBlock()
    {
        if ($this->hasBlock(self::$_currentBlock)) {
            $content = self::$_blocks[self::$_currentBlock];
            $content .= ob_get_clean();
            self::$_blocks[self::$_currentBlock] = $content;
        } else {
            self::$_blocks[self::$_currentBlock] = ob_get_clean();
        }
        self::$_currentBlock = NULL;
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

    protected function includeTemplate($filename, $data = array())
    {
        $tmpl = new self($data);
        return $tmpl->render($filename);
    }

    public function render($path)
    {
        $root = Configuration::$templateRoot;
//        $dirs = Configuration::$templateDirectories;
        $extensions = Configuration::$templateExtensions;
        $paths = is_array($path) ? $path : array($path);
//        foreach ($dirs as $dir) {
        foreach ($extensions as $ex) {
            foreach ($paths as $subPath) {
                $file = PathUtil::combines($root, $subPath, $ex);
                if (file_exists($file)) {
                    if (Configuration::$trace) {
                        Configuration::$logger->v($file, 'template');
                    }
                    ob_start();
                    include $file;
                    return ob_get_clean();
                }
            }
        }
//        }

        throw new \Exception('Not found template:' . $path);
    }

    private static $_helpers = array();

    static public function addHelper($name, $func)
    {
        self::$_helpers[$name] = $func;
    }
}

