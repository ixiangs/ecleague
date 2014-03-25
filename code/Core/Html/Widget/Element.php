<?php
namespace Core\Html\Widget;

use Toy\Util\StringUtil;

class Element
{

    private $_tag = null;
    private $_children = array();
    private $_bindableAttributes = array();
    private $_boundAttributes = array();
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
        return $this->_children;
    }

    public function setChildren($value)
    {
        $this->_children = $value;
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
        }
        return $this;
    }

    public function getBindableAttribute()
    {
        return $this->_bindableAttributes;
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

    public function bindAttribute($data)
    {
        foreach ($this->attributes as $k => $v) {
            if (in_array($k, $this->_bindableAttributes)) {
                $nv = $v;
                foreach ($data as $dk => $dv) {
                    $nv = str_replace('{' . $dk . '}', $dv, $nv);
                }
                $this->attributes[$k] = $nv;
            }
        }
        return $this;
    }

    public function renderAttribute()
    {
        $arr = array();
        foreach ($this->attributes as $k => $v) {
            if ($k != 'text') {
                $arr[] = $k . '="' . $v . '"';
            }
        }
        return implode(' ', $arr);
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
        if (array_key_exists('text', $this->attributes)) {
            $res = $this->attributes['text'];
        }
        foreach ($this->_children as $child) {
            $res .= $child->render();
        }
        return $res;
    }

    public function render()
    {
        switch ($this->_tag) {
            case 'input':
                return '<' . $this->_tag . ' ' . $this->renderAttribute() . '/>';
            default:
                return $this->renderBegin() . $this->renderInner() . $this->renderEnd();
        }
    }
}
