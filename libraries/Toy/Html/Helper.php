<?php
namespace Toy\Html;

class Helper
{

    private function __construct()
    {
    }

    public function anchor($text, $href = '#', $target = null)
    {
        return new Element('a', array('href' => $href, 'text' => $text, 'target' => $target));
    }

    public function button($type, $text, $css = 'btn btn-default')
    {
        return new Element('button', array('type' => $type, 'class' => $css, 'text' => $text));
    }

    public function grid($dataSource = null, $id = 'table1')
    {
        return new Grid($dataSource, $id);
    }

    public function form($id = 'form1', $method = 'post')
    {
        return new Form($id, $method);
    }

    public function groupedForm($id = 'form1', $method = 'post')
    {
        return new GroupedForm($id, $method);
    }

    public function pagination($total, $ps, $pr)
    {
        return new Pagination($total, $ps, $pr);
    }

    public function dropdown($button, $attrs = array('class' => "btn-group"))
    {
        return new Dropdown($button, $attrs);
    }

    public function buttonGroup($buttons = array())
    {
        $res = new ButtonGroup();
        return $res->setChildren($buttons);
    }

    public function inputGroup()
    {
        return new InputGroup();
    }

    public function textbox($id = null, $name = null, $value = null, $type = 'text')
    {
        $res = new Textbox('input');
        $res->setAttribute(array('type' => $type, 'id' => $id, 'name' => $name, 'value' => $value, 'class' => "form-control"));
        return $res;
    }

    public function textarea($id = null, $name = null, $value = null)
    {
        $res = new Textarea();
        $res->setAttribute(array('id' => $id, 'name' => $name, 'value' => $value, 'class' => "form-control"));
        return $res;
    }

    public function select($id = null, $name = null, $value = null, $options = array())
    {
        $res = new Select(array('id' => $id, 'name' => $name, 'value' => $value, 'class' => "form-control"));
        $res->setOptions($options);
        return $res;
    }

    public function treeSelect($id = null, $name = null, $value = null, $options = array())
    {
        $res = new TreeSelect(array('id' => $id, 'name' => $name, 'value' => $value, 'class' => "form-control"));
        $res->setOptions($options);
        return $res;
    }

    public function optionList($id = null, $name = null, $value = null, $options = array())
    {
        $res = new OptionList(array('id' => $id, 'name' => $name, 'value' => $value, 'class' => "form-control"));
        $res->setOptions($options);
        return $res;
    }

    public function newElement($tag, array $attrs = array())
    {
        return new Element($tag, $attrs);
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
