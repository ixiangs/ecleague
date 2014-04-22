<?php
namespace Toy\View\Html;

use Toy\Util\StringUtil;

class Element
{

    private $_tag = null;
    protected $children = array();
    private $_bindableAttributes = array();
    private $_boundAttributes = array();
    protected $renderer = null;
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

    public function getChildren()
    {
        return $this->children;
    }

    public function setChildren($value)
    {
        $this->children = $value;
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

    public function addBindableAttribute()
    {
        $args = func_get_args();
        $this->_bindableAttributes = array_merge($this->_bindableAttributes, $args);
        return $this;
    }

    public function removeBindableAttribute()
    {
        $args = func_get_args();
        foreach ($args as $arg) {
            $k = array_search($arg, $this->_bindableAttributes);
            unset($this->_bindableAttributes[$k]);
            if (array_key_exists($arg, $this->_boundAttributes)) {
                unset($this->_boundAttributes[$arg]);
            }
        }
        return $this;
    }

    public function getBindableAttribute()
    {
        return $this->_bindableAttributes;
    }

    public function getBoundAttribute()
    {
        return $this->_boundAttributes;
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
            $this->attributes['on' . $args[0]] = 'javascript:' . $args[1];
        } elseif ($nums == 1 && is_array($args[0])) {
            foreach ($args as $k => $v) {
                $this->attributes['on' . $k] = 'javascript:' . $v;
            }
        }
        return $this;
    }

    public function bindAttribute($data)
    {
        $this->_boundAttributes = array();
        foreach ($this->_bindableAttributes as $k) {
            if (array_key_exists($k, $this->attributes)) {
                $this->_boundAttributes[$k] = StringUtil::substitute($this->attributes[$k], $data);
            }
        }
        return $this;
    }

    public function renderAttribute()
    {
        $arr = array();
        foreach ($this->_boundAttributes as $k => $v) {
            if ($k != 'text') {
                if (!empty($v)) {
                    $arr[] = $k . '="' . $v . '"';
                }
            }
        }

        foreach ($this->attributes as $k => $v) {
            if ($k != 'text' && !array_key_exists($k, $this->_boundAttributes)) {
                if (!empty($v)) {
                    $arr[] = $k . '="' . $v . '"';
                }
            }
        }

        return implode(' ', $arr);
    }

    public function addChild()
    {
        $args = func_get_args();
        $this->children = array_merge($this->children, $args);
        return $this;
    }

    public function getChild($index)
    {
        return $this->children[$index];
    }

    public function renderBegin()
    {
        return '<' . $this->_tag . ' ' . $this->renderAttribute() . '>';
    }

    public function renderEnd()
    {
        return '</' . $this->_tag . '>';
    }

    public function renderInner()
    {
        $res = '';
        if (array_key_exists('text', $this->_boundAttributes)) {
            $res = $this->_boundAttributes['text'];
        } elseif (array_key_exists('text', $this->attributes)) {
            $res = $this->attributes['text'];
        }
        $res .= $this->renderChildren();
        return $res;
    }

    protected function renderChildren()
    {
        $res = '';
        foreach ($this->children as $child) {
            $res .= $child->render();
        }
        return $res;
    }

    public function render()
    {
        if (!is_null($this->renderer)) {
            $r = call_user_func($this->renderer, $this);
            return $r;
        }

        switch ($this->_tag) {
            case 'input':
                return '<' . $this->_tag . ' ' . $this->renderAttribute() . '/>';
            case 'text':
                return $this->attributes['text'];
            default:
                return $this->renderBegin() . $this->renderInner() . $this->renderEnd();
        }
    }
}
