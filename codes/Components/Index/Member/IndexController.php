<?php
namespace Components\Index\Member;

use Toy\Web;

class IndexController extends Web\Controller
{

    public function IndexAction()
    {
        return Web\Result::templateResult();
    }
}