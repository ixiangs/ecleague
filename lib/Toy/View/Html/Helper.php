<?php
namespace Toy\View\Html;

class Helper
{

    private function __construct()
    {
    }

    public function anchor($text, $href = '#', $target=null){
        return new Element('a', array('href'=>$href, 'text'=>$text, 'target'=>$target));
    }

    public function button($type, $text, $css = 'btn btn-default'){
        return new Element('button', array('type'=>$type, 'class'=>$css, 'text'=>$text));
    }

    public function grid($dataSource = null, $id = 'table1'){
        return new Grid($dataSource, $id);
    }

    public function form($id = 'form1', $method='post'){
        return new Form($id, $method);
    }

    public function groupedForm($id = 'form1', $method='post'){
        return new GroupedForm($id, $method);
    }

    public function pagination($total, $ps, $pr){
        return new Pagination($total, $ps, $pr);
    }

    public function dropdownButton($button, $attrs = array('class' => "btn-group")){
        return new DropdownButton($button, $attrs);
    }

    public function buttonGroup($buttons = array()){
        $res = new ButtonGroup();
        return $res->setChildren($buttons);
    }

    public function inputGroup(){
        return new InputGroup();
    }

    public function input($type, $id = null, $name = null, $value = null){
        return new Element('input', array('type'=>$type, 'id'=>$id, 'name'=>$name, 'value'=>$value, 'class'=>"form-control" ));
    }

    public function newElement($tag, array $attrs = array()){
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
