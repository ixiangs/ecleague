<?php
namespace Core\Html;

use Core\Html\Widget\Element;
use Core\Html\Widget\Table, Core\Html\Widget\Form;
use Core\Html\Widget\Pagination;

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

    public function table($dataSource = null, $id = 'table1'){
        return new Table($dataSource, $id);
    }

    public function form($id = 'form1', $method='post'){
        return new Form($id, $method);
    }

    public function pagination($total, $ps, $pr){
        return new Pagination($total, $ps, $pr);
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
