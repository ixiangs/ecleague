<?php
namespace Components\Admin\Backend;

use Toy\Web;

class IndexController extends Web\Controller
{

    public function IndexAction()
    {
        return Web\Result::templateResult();
    }
}