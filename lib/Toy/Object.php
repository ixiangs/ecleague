<?php
namespace Toy;

class Object implements \ArrayAccess, \Iterator
{
    private static $_camelCaseToUnderline = array();

    protected $data = array();

    public function __construct($data = array()){}

    public function __get($name)
    {
        return $this->getData($name);
    }

    public function __set($name, $value)
    {
        $this->setData($name, $value);
    }

    public function __call($name, $arguments)
    {
        $nums = count($arguments);
        $st = substr($name, 0, 3);
        if ($st == 'get') {
            $pn = self::getUnderlineName(substr($name, 3));
            if ($nums == 1) {
                return $this->getData($pn, $arguments[0]);
            }
            return $this->getData($pn);
        } elseif ($st == 'set') {
            $pn = self::getUnderlineName(substr($name, 3));
            return $this->setData($pn, $arguments[0]);
        }
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->data);
    }

    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->data[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    public function current()
    {
        return current($this->data);
    }

    public function key()
    {
        return key($this->data);
    }

    public function next()
    {
        return next($this->data);
    }

    public function rewind()
    {
        return reset($this->data);
    }

    public function valid()
    {
        return key($this->data) !== null;
    }

    public function isEmptyProperty($name)
    {
        if (!array_key_exists($name, $this->data)) {
            return true;
        }
        return empty($this->data[$name]);
    }

    public function getData($name, $default = null)
    {
        if (array_key_exists($name, $this->data)) {
            if (is_null($this->data[$name])) {
                return $default;
            }
            return $this->data[$name];
        }
        return $default;
    }

    public function setData($name, $value)
    {
        $this->data[$name] = $value;
        return $this;
    }

    public function getAllData(){
        return $this->data;
    }

    public function setAllData($value){
        $this->data = $value;
        return $this;
    }

    protected static function getUnderlineName($camelCase)
    {
        if (!array_key_exists($camelCase, self::$_camelCaseToUnderline)) {
            preg_match_all('/([A-Z]{1}[a-z0-9]+)/', $camelCase, $matches);
            self::$_camelCaseToUnderline[$camelCase] = implode('_', array_map('lcfirst', $matches[0]));
        }
        return self::$_camelCaseToUnderline[$camelCase];
    }
}
