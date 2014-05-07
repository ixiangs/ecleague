<?php
namespace Ixiangs\System;

use Toy\View\Template;
use Toy\Web\Application;

class Listener
{
    static public function webOnStart($app, $argument)
    {
        $context = Application::$context;
        $lang = $context->request->getBrowserLanguage();
        $l = Localize::singleton();
        $l->initialize($lang);
        Template::addHelper('locale', $l);
        $context->locale = $l;
    }
}
