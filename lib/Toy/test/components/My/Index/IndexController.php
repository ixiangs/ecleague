<?php
namespace Index;

use Toy\Web\Controller;

class IndexController extends Controller
{

    public function indexAction()
    {
        return '\frontend\index\index\index';
    }

    public function twoAction($first = null, $second = null)
    {
        return $first . '|' . $second;
    }
}
