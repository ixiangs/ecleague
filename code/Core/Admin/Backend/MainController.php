<?php
namespace Core\Admin\Backend;

use Core\Admin\Model\AdministratorModel;
use Toy\Web;

class MainController extends Web\Controller
{

    public function dashboardAction()
    {
        return Web\Result::templateResult();
    }
}