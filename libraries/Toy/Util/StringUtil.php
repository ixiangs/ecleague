<?php
namespace Toy\Util;

class StringUtil
{

    static public function isGbk($str)
    {
        return preg_match('/[\x80-\xff]./', $str);
    }

    static public function converToUtf8($str)
    {
        if (self::isGbk($str)) {
            return mb_convert_encoding($str, 'UTF-8', 'GBK');
        }
        return $str;
    }

    static public function lowerFirstChar($str)
    {
        return lcfirst($str);
    }

    static public function upperFirstChar($str)
    {
        return ucfirst($str);
    }

    static public function SplitToPascalCasing($split, $arg)
    {
        return str_replace(' ', '', ucwords(str_replace($split, ' ', $arg)));
    }

    static public function PascalCasingToUnderline($arg)
    {
        return strtolower(preg_replace('/(.)([A-Z])/', "$1_$2", $arg));
    }

    static public function PascalCasingToDash($arg)
    {
        return strtolower(preg_replace('/(.)([A-Z])/', "$1-$2", $arg));
    }

    static public function substitute($str, $values)
    {
        $result = $str;

        if (preg_match_all('/(\{\w+\})/i', $str, $matches)) {
            foreach ($matches[0] as $match) {
                $key = substr($match, 1, -1);
                if (array_key_exists($key, $values) || isset($values[$key])) {
                    $v = $values[$key];
                    $result = str_replace($match, $v, $result);
                }
            }
        }
        return $result;
    }

    static public function startsWith($str, $search)
    {
        return substr($str, 0, strlen($search)) == $search;
    }

    static public function endsWith($str, $search)
    {
        $searchLen = strlen($search);
        $strLen = strlen($str);
        return substr($str, $strLen - $searchLen) == $search;
    }

}
