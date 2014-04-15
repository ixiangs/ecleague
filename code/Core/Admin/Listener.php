<?php
namespace Core\Admin;

use Toy\Web\Template;

class Listener
{

    static public function applicationOnStart($app, $argument)
    {
        $ctx = $app->getContext();
    }

}
