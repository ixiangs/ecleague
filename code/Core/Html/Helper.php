<?php
namespace Core\Html;

use Core\Html\Widget\DataTable;
use Toy\Web\Application;

class Helper
{

    private function __construct()
    {
    }

    public function dataTable($dataSource = null, $id = null){
        return new DataTable($dataSource, $id);
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

    public function field($label, $input)
    {
        $html = array('<div class="form-group">');
        $html[] = '<label class="control-label">'.$label.'</label>';
        $html[] = $input;
        $html[] = '</div>';

        return implode('', $html);
    }

    public function pagination($total, $ps, $pr){
        $router = Application::singleton()->getContext()->router;
        $request = Application::singleton()->getContext()->request;
        $pageIndex = $request->getParameter('pageindex', 1);
        $pc = ceil($total / $ps);
        $start = 0;
        $end = 0;
        if($pc < $pr){
            $start = 1;
            $end = $pc;
        } else if($pageIndex == $pc){
            $start = $pc - $pr - 1;
            $end = $pc;
        } else {
            $pr = floor($pageIndex / $pr);
            $start = $pr <= 0? 1: $pr*$pr - 1;
            $end = $start + $pr + 1;
            if($end > $pc) {
                $start = $pc - $pr - 1;
                $end = $pc;
            }
            if($start < 1){
                $start = 1;
                $end = $start + $pr;
            }
        }

        $pargs = $request->getAllParameters();
        $pargs['pageindex'] = 1;
        $html = array(
            "<ul class=\"pagination  pull-right\">",
        );
        if($pageIndex > $pr) {
            $html[] = sprintf("<li><a href=\"%s\">&larr; %s</a></li>", $router->buildUrl().'?'.http_build_query($pargs) , 1);
        }

        for($i = $start; $i <= $end; $i++){
            if($i == $pageIndex) {
                $html[] = sprintf("<li class=\"active\"><span>%s</span></li>", $i);
            } else {
                $pargs['pageindex'] = $i;
                $html[] = sprintf("<li><a href=\"%s\">%s</a></li>", $router->buildUrl().'?'.http_build_query($pargs), $i);
            }
        }

        if($pageIndex < $pc-$pr) {
            $pargs['pageindex'] = $pc;
            $html[] = sprintf("<li><a href=\"%s\">%s &rarr;</a></li>", $router->buildUrl().'?'.http_build_query($pargs), $pc);
        }
        $html[] = '</url>';
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
