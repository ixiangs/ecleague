<?php
namespace Ixiangs\System;

use Toy\Web;

class MainController extends Web\Controller
{

    public function dashboardAction()
    {
        return Web\Result::templateResult();
    }
}