<?php
namespace Toys\Util;

final class ArrayUtil
{

    public static function count(array $arr, $function)
    {
        $result = 0;
        foreach ($arr as $index => $item) {
            if ($function($item, $index)) {
                $result++;
            }
        }
        return $result;
    }

    public static function join($seq, array $arr, $function)
    {
        $result = '';
        foreach ($arr as $key => $item) {
            $result .= $function($item, $key);
        }
        return $result;
    }

    public static function each(array &$arr, $function)
    {
        foreach ($arr as $index => $item) {
            $function($item, $index);
        }
    }

    public static function find(array $arr, $function)
    {
        foreach ($arr as $index => $item) {
            if ($function($item, $index)) {
                return $item;
            }
        }
    }

    public static function filter(array $arr, $function)
    {
        $result = array();
        foreach ($arr as $index => $item) {
            if ($function($item, $index)) {
                $result[] = $item;
            }
        }
        return $result;
    }

    public static function extract(array $arr, $key)
    {
        $result = array();
        foreach ($arr as $item) {
            if (array_key_exists($key, $item)) {
                $result[] = $item[$key];
            }
        }
        return $result;
    }

    public static function contains(array $arr, $function)
    {
        foreach ($arr as $item) {
            if ($function($item, $index)) {
                return TRUE;
            }
        }
        return false;
    }

    public static function toArray(array $arr, $function)
    {
        $result = array();
        foreach ($arr as $index => $item) {
            $result[] = $function($item, $index);
        }
        return $result;
    }

    public static function get(array $arr, $key, $default = NULL)
    {
        if (array_key_exists($key, $arr)) {
            return $arr[$key];
        }
        return $default;
    }

    // public static function getNotEmpty(array $arr, $key, $default = NULL) {
    // if (array_key_exists($key, $arr) && !empty($arr[$key])) {
    // return $arr[$key];
    // }
    // return $default;
    // }

    public static function removeEmpty(array &$arr)
    {
        foreach ($arr as $key => $item) {
            if (empty($item)) {
                unset($arr[$key]);
            } elseif (is_array($item)) {
                $arr[$key] = self::removeEmpty($item);
            }
        }
        return $arr;
    }

    public static function compare(array $arr1, array $arr2)
    {
        if (count($arr1) != count($arr2)) {
            return false;
        }

        foreach ($arr1 as $k1 => $v1) {
            if (!array_key_exists($k1, $arr2)) {
                return false;
            }

            if (is_array($v1) && is_array($arr2[$k1])) {
                if (!self::compare($v1, $arr2[$k1])) {
                    return false;
                }
            }

            if ($v1 !== $arr2[$k1]) {
                return false;
            }
        }

        return TRUE;
    }

    public static function hasAllKeys($arr, array $keys)
    {
        foreach ($keys as $v) {
            if (!array_key_exists($v, $arr)) {
                return false;
            }
        }

        return true;
    }

    public static function hasAnyKeys($arr, array $keys)
    {
        foreach ($keys as $v) {
            if (array_key_exists($v, $arr)) {
                return true;
            }
        }

        return false;
    }

    public static function pack($value)
    {
        if (is_array($value)) {
            return $value;
        }
        return array($value);
    }

}
