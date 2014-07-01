<?php
namespace My\One;

use Toy\Web\Controller;

class FIndexController extends Controller
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
