<?php
namespace Core\Locale;

use Core\Locale\Model\DictionaryModel;
use Core\Locale\Model\LanguageModel;

class Localize implements \ArrayAccess
{

    private $_texts = array();
    private $_languages = array();
    private $_currentLanguage = null;

    private function __construct()
    {
    }

    public function __get($name)
    {
        return $this->_texts[$name];
    }

    public function getText($name, $default = ''){
        if(array_key_exists($name, $this->_texts)){
            return $this->_texts[$name];
        }
        return $default;
    }

    public function _()
    {
        $args = func_get_args();
        if(array_key_exists($args[0], $this->_texts)){
            if (count($args) > 1) {
                $args[0] = $this->_texts[$args[0]];
                return call_user_func_array('sprintf', $args);
            } else {
                return $this->_texts[$args[0]];
            }
        }
        return '';
    }

    public function getLanguages(){
        return $this->_languages;
    }

    public function formatDate($format, $time = null)
    {
        $nt = $time ? $time : time();
        switch ($format) {
            case 'L':
                return date($this->_currentLanguage['long_date_formate'], $nt);
            case 'S':
                return date($this->_currentLanguage['long_short_formate'], $nt);
            case 'LF':
                return date($this->_currentLanguage['long_date_formate'].' H:i:s', $nt);
            case 'SF':
                return date($this->_currentLanguage['long_short_formate'].' H:i:s', $nt);
            default:
                return date($format, $nt);
        }
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->_texts);
    }

    public function offsetGet($offset)
    {
        return $this->_texts[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->_texts[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->_texts[$offset]);
    }

    public function initialize($lang)
    {
        $rows = LanguageModel::find()->load();
        foreach($rows as $row){
            $this->_languages[strtolower($row->getCode())] = $row->getAllData();
        }
        $this->_currentLanguage = $this->_languages[$lang];

        $this->_texts = DictionaryModel::find()
                            ->eq('language_id', $this->_currentLanguage['id'])->load()
                            ->toArray(function(&$arr, $item){
                                $arr[$item->getCode()] = $item->getLabel();
                            });
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
