<?php
namespace Core\Locale;

use Core\Locale\Model\LanguageModel;
use Toy\Platform\PathUtil;
use Toy\Platform\FileUtil;
use Toy\Web\Application;

class Localize implements \ArrayAccess
{

    private $_texts = array();
    private $_languages = array();
    private $_currentLanguage = null;
//    private $_longDateFormat = "";
//    private $_shortDateFormat = "";

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
        $rows = LanguageModel::find()->execute()->rows;
        foreach($rows as $row){
            $this->_languages[strtolower($row['code'])] = $row;
        }
        $this->_currentLanguage = $this->_languages[$lang];

        $settings = Application::$componentSettings['Locale']['settings'];
        $path = PathUtil::combines($settings['directory'], $lang);
        PathUtil::scanCurrent($path, function ($file, $info) use (&$files) {
            if ($info['basename'] == 'culture.csv') {
//                $lines = FileUtil::readCsv($file);
//                for ($i = 0; $i < count($lines); $i++) {
//                    switch ($lines[$i][0]) {
//                        case 'longDate' :
//                        {
//                            $this->_longDateFormat = $lines[$i][1];
//                            break;
//                        }
//                        case 'shortDate' :
//                        {
//                            $this->_shortDateFormat = $lines[$i][1];
//                            break;
//                        }
//                    }
//                }
            } elseif ($info['extension'] == 'csv') {
                $lines = FileUtil::readCsv($file);
                for ($i = 0; $i < count($lines); $i++) {
                    $this->_texts[$lines[$i][0]] = $lines[$i][1];
                }
            }
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
