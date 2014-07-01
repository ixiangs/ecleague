<?php
namespace Toy\Web;

class Localize implements \ArrayAccess
{

    private $_dictionaries = array();
    private $_languages = array();
    private $_currentLanguage = null;

    private function __construct()
    {
    }

    public function __get($name)
    {
        return $this->_dictionaries[$name];
    }

    public function _()
    {
        $args = func_get_args();
        if (array_key_exists($args[0], $this->_dictionaries)) {
            if (count($args) > 1) {
                $args[0] = $this->_dictionaries[$args[0]];
                return call_user_func_array('sprintf', $args);
            } else {
                return $this->_dictionaries[$args[0]];
            }
        }
        return '';
    }

    public function getCurrentLanguage()
    {
        return $this->_currentLanguage;
    }

    public function setCurrentLanguage($value)
    {
        $this->_currentLanguage = $value;
        return $this;
    }

    public function getLanguages()
    {
        return $this->_languages;
    }

    public function formatDate($format, $time = null)
    {
        $nt = $time ? $time : time();
        switch ($format) {
            case 'L':
                return date($this->_currentLanguage['date_long_format'], $nt);
            case 'S':
                return date($this->_currentLanguage['date_short_format'], $nt);
            case 'LF':
                return date($this->_currentLanguage['date_long_format'] . ' H:i:s', $nt);
            case 'SF':
                return date($this->_currentLanguage['date_short_format'] . ' H:i:s', $nt);
            default:
                return date($format, $nt);
        }
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->_dictionaries);
    }

    public function offsetGet($offset)
    {
        return $this->_dictionaries[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->_dictionaries[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->_dictionaries[$offset]);
    }

    public function initialize()
    {
//        $rows = LanguageModel::find()->load();
//        foreach ($rows as $row) {
//            $this->_languages[strtolower($row->getCode())] = $row->getAllData();
//        }
//        $this->_currentLanguage = $this->_languages[$lang];
//
//        $this->_dictionaries = DictionaryModel::find()
//            ->eq('language_id', $this->_currentLanguage['id'])->load()
//            ->toArray(function ($item) {
//                return array($item->getCode(), $item->getLabel());
//            });
    }

    private static $_instance = NULL;

    static public function singleton()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}
