<?php
namespace Toy\Html;

use Toy\Util\StringUtil;

class Element
{

    private $_tag = null;
    protected $renderer = null;
    protected $children = array();
    protected $events = array();
    protected $attributes = array();

    public function __construct($tag, $attrs = array())
    {
        $this->_tag = $tag;
        $this->attributes = $attrs;
    }

    public function getTag()
    {
        return $this->_tag;
    }

    public function setTag($value)
    {
        $this->_tag = $value;
        return $this;
    }

    public function getChild($index)
    {
        return $this->children[$index];
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function setChildren($value)
    {
        $this->children = $value;
        return $this;
    }

    public function addChild()
    {
        $args = func_get_args();
        $this->children = array_merge($this->children, $args);
        return $this;
    }

    public function getRenderer()
    {
        return $this->renderer;
    }

    public function setRenderer(\Closure $value)
    {
        $this->renderer = $value;
        return $this;
    }

    public function getAttribute()
    {
        $args = func_get_args();
        $nums = func_num_args();
        if ($nums > 1) {
            $res = array();
            foreach ($args as $arg) {
                if (array_key_exists($arg, $this->attributes)) {
                    $res[] = $this->attributes[$arg];
                }
            }
            return $res;
        } elseif ($nums == 1) {
            if (array_key_exists($args[0], $this->attributes)) {
                return $this->attributes[$args[0]];
            }
            return null;
        } else {
            return $this->attributes;
        }
    }

    public function setAttribute()
    {
        $args = func_get_args();
        $nums = func_num_args();
        if ($nums == 2) {
            $this->attributes[$args[0]] = $args[1];
        } elseif ($nums == 1 && is_array($args[0])) {
            $this->attributes = array_merge($this->attributes, $args[0]);
        }
        return $this;
    }

    public function removeAttribute()
    {
        $args = func_get_args();
        $nums = func_num_args();
        if ($nums > 1) {
            foreach ($args as $arg) {
                if (array_key_exists($arg, $this->attributes)) {
                    unset($this->attributes[$arg]);
                }
            }
        } elseif ($nums == 1) {
            unset($this->attributes[$args[0]]);
        } else {
            $this->attributes = array();
        }

        return $this;
    }

    public function setEvent()
    {
        $args = func_get_args();
        $nums = func_num_args();
        if ($nums == 2) {
            $this->events[$args[0]] = $args[1];
        } elseif ($nums == 1 && is_array($args[0])) {
            foreach ($args as $k => $v) {
                $this->events[$args[$k]] = $v;
            }
        }
        return $this;
    }

    public function renderAttribute($data = array())
    {
        $arr = array();
        foreach ($this->attributes as $k => $v) {
            if ($k != 'text') {
                if (!empty($v)) {
                    $arr[] = $k . '="' .
                        ($v[0] == '@'? StringUtil::substitute(substr($v, 1), $data): $v) . '"';
                }
            }
        }
        foreach ($this->events as $k => $v) {
            if (!empty($v)) {
                $arr[] = 'on'.strtolower($k) . '="' .
                    ($v[0] == '@'?
                        'javascript:'.StringUtil::substitute(substr($v, 1), $data):
                        'javascript:'.$v) . '"';
            }
        }

        return implode(' ', $arr);
    }

    public function renderBegin($data = array())
    {
        return '<' . $this->_tag . ' ' . $this->renderAttribute($data) . '>';
    }

    public function renderEnd()
    {
        return '</' . $this->_tag . '>';
    }

    public function renderInner($data = array())
    {
        $res = '';
        if (array_key_exists('text', $this->attributes)) {
            $txt = $this->attributes['text'];
            if(!empty($txt)){
                $res = $txt[0] == '@'? StringUtil::substitute(substr($txt, 1), $data): $txt;
            }
        }

        $res .= $this->renderChildren($data);
        return $res;
    }

    protected function renderChildren($data = array())
    {
        $res = '';
        foreach ($this->children as $child) {
            $res .= $child->render($data);
        }
        return $res;
    }

    public function render($data = array())
    {
        if (!is_null($this->renderer)) {
            return call_user_func($this->renderer, $this);
        }

        switch ($this->_tag) {
            case 'input':
                return '<' . $this->_tag . ' ' . $this->renderAttribute($data) . '/>';
            case 'text':
                return $this->attributes['text'];
            default:
                return $this->renderBegin($data) . $this->renderInner($data) . $this->renderEnd();
        }
    }
}
