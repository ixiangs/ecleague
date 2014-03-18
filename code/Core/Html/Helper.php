<?php
namespace Core\Html;

class Helper
{

    private function __construct()
    {
    }

    public function beginForm()
    {
        return new Form();
    }

    public function input($type, $id, $name, $class, $value, array $attrs = array())
    {
        $attrs['type'] = $type;
        $attrs['id'] = $id;
        $attrs['name'] = $name;
        $attrs['value'] = $value;
        $attrs['class'] = $class;
        $arr = array();
        foreach ($attrs as $k => $v) {
            $arr[] = "$k=\"$v\"";
        }
        return '<input ' . implode(' ', $arr) . '/>';
    }

    public function select($caption, $items, $id, $name, $class, $value, array $attrs = array())
    {
        $attrs['id'] = $id;
        $attrs['name'] = $name;
        $attrs['class'] = $class;
        $arr = array();
        foreach ($attrs as $k => $v) {
            $arr[] = "$k=\"$v\"";
        }
        $html = array('<select ' . implode(' ', $arr) . '/>');
        if (!empty($caption)) {
            if (is_string($caption)) {
                $html[] = '<option value="">' . $caption . '</option>';
            } elseif (is_array($caption)) {
                $ks = array_keys($caption);
                $vs = array_values($caption);
                $html[] = '<option value="' . $ks[0] . '">' . $vs[0] . '</option>';
            }
        }

        foreach ($items as $option => $text) {
            if ($value == $option) {
                $html[] = "<option value=\"$option\" selected>$text</option>";
            } else {
                $html[] = "<option value=\"$option\">$text</option>";
            }
        }
        $html[] = '</select>';
        return implode('', $html);
    }

    public function groupSelect($caption, $items, $id, $name, $class, $value, array $attrs = array())
    {
        $attrs['id'] = $id;
        $attrs['name'] = $name;
        $attrs['class'] = $class;
        $arr = array();
        foreach ($attrs as $k => $v) {
            $arr[] = "$k=\"$v\"";
        }
        $html = array('<select ' . implode(' ', $arr) . '/>');
        if (!empty($caption)) {
            if (is_string($caption)) {
                $html[] = '<option value="">' . $caption . '</option>';
            } elseif (is_array($caption)) {
                $ks = array_keys($caption);
                $vs = array_values($caption);
                $html[] = '<option value="' . $ks[0] . '">' . $vs[0] . '</option>';
            }
        }

        foreach ($items as $item) {
            $html[] = '<optgroup label="' . $item['label'] . '">';
            foreach ($item['options'] as $option => $text) {
                if ($value == $option) {
                    $html[] = "<option value=\"$option\" selected>$text</option>";
                } else {
                    $html[] = "<option value=\"$option\">$text</option>";
                }
            }
            $html[] = '</optgroup>';
        }
        $html[] = '</select>';
        return implode('', $html);
    }

    public function checkboxes($items, $name, $class, array $values = array(), array $attrs = array())
    {
        $html = array();
        foreach ($items as $option => $text) {
            if (in_array($option, $values)) {
                $html[] = "<label class=\"checkbox-inline\"><input type=\"checkbox\" name=\"$name\" value=\"$option\" checked=\"true\">$text</label>";
            } else {
                $html[] = "<label class=\"checkbox-inline\"><input type=\"checkbox\" name=\"$name\" value=\"$option\">$text</label>";
            }
        }
        $html[] = '</select>';
        return implode('', $html);
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
