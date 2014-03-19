<?php
namespace Core\Html;

use Core\Html\Widget\Table, Core\Html\Widget\Form;
use Core\Html\Widget\Pagination;

class Helper
{

    private function __construct()
    {
    }

    public function dataTable($dataSource = null, $id = null){
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
