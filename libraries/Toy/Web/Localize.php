<?php
namespace Toy\Web;

use Toy\Platform\FileUtil;
use Toy\Platform\PathUtil;

class Localize implements \ArrayAccess
{

    private $_dictionaries = array();
    private $_languages = array();
    private $_currentLanguage = null;

    public function __construct()
    {
        $data = FileUtil::readJson(Configuration::$languagePath . 'languages.json');
        foreach ($data as $line) {
            $this->_languages[$line['code']] = $line;
        }
        $this->loadDictionary('zh-CN');
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

    public function loadDictionary($languageCode)
    {
        $this->_dictionaries = array();
        PathUtil::scanCurrent(Configuration::$languagePath . $languageCode,
            function ($path, $info) {
                $data = FileUtil::readCsv($path);
                foreach($data as $line){
                    $this->_dictionaries[$line[0]] = $line[1];
                }
            });
        return $this;
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
}
