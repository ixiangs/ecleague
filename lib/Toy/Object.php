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

    public function isEmptyData($name)
    {
        if (!array_key_exists($name, $this->data)) {
            return true;
        }
        return empty($this->data[$name]);
    }

    public function getData()
    {
        $args = func_get_args();
        $nums = func_num_args();
        if($nums == 2){
            if (array_key_exists($args[0], $this->data)) {
                $res = $this->data[$args[0]];
                return is_null($res)? $args[1]: $res;
            }else{
                return $args[1];
            }
        }elseif($nums == 1){
            if (array_key_exists($args[0], $this->data)) {
                return $this->data[$args[0]];
            }
            return null;
        }

        return $this->data;
    }

    public function setData($name, $value)
    {
        $args = func_get_args();
        $nums = func_num_args();
        if($nums == 2){
            $this->data[$args[0]] == $args[1];
        }elseif($nums == 1 && is_array($args[0])){
            $this->data = array_merge($this->data, $args[0]);
        }
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
