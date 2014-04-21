<?php
namespace Core\Locale;

use Toy\View\Template;
use Toy\Web\Application;

class Listener
{

    static public function webOnStart($app, $argument)
    {
        $ctx = Application::$context;
        $lang = $ctx->request->getBrowserLanguage();
        $l = Localize::singleton();
        $l->initialize($lang);
        Template::addHelper('locale', $l);
        $ctx->locale = $l;
    }

}
