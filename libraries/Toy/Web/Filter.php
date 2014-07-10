<?php
namespace Toy\Web;

class Filter
{

    public function filtrate()
    {
        array_walk_recursive($_POST, function(&$v, $k){
            $v = htmlspecialchars($v);
        });
    }

}
