<?php
namespace Core\Locale;

use Toy\Web\Template;

class Listener
{

    static public function applicationOnStart($app, $argument)
    {
        $ctx = $app->getContext();
        $lang = $ctx->request->getBrowserLanguage();
        $l = Localize::singleton();
        $l->initialize($lang);
        Template::addHelper('locale', $l);
        $ctx->locale = $l;
    }

}
