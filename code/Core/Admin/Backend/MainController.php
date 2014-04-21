<?php
namespace Core\Admin\Backend;

use Toy\Web;

class MainController extends Web\Controller
{

    public function dashboardAction()
    {
        return Web\Result::templateResult();
    }
}