<?php
namespace Ixiangs\System;

use Toy\View\Html\Document;
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

    static public function webPostRoute($app, $argument){
        $component = Application::getRequestComponent();
        if($component){
            Document::singleton()->setBreadcrumbs($component->getActionBreadcrumb());
        }
    }

    static public function webPostRender($app, $argument){
        $locale = Localize::singleton();
        $content = Application::$context->response->getContent();
        preg_match_all('/{@_\s+(\w+)}/i', $content, $matches);
        $count = count($matches[0]);
        for($i = 0; $i < $count; $i++){
            $text = $locale->_($matches[1][$i]);
            $content = str_replace($matches[0][$i], $text, $content);
        }
        Application::$context->response->write($content);
    }
}
