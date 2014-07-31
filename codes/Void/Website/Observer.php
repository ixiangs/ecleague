<?php
namespace Void\Website;

use Toy\Html\Document;
use Toy\Web\Configuration;
use Toy\Web\Application;

class Observer
{

    static public function webPostRoute($source, $argument)
    {
        $router = Application::$context->router;
        $request = Application::$context->request;
        $session = Application::$context->session;
        if ($router->domain->getName() == 'mobile') {
            $website = WebsiteModel::find()
                ->eq('id', $request->getQuery('websiteid'))
                ->load()
                ->getFirst();
            Application::$context->website = $website;
            Configuration::$templateTheme = $website->getTheme();
            Document::singleton()->setTitle($website->getTitle());
        }
    }

    static public function authOnLogin($source, $argument)
    {
        $router = Application::$context->router;
        if ($router->domain->getName() == 'member') {
            $website = WebsiteModel::find()
                ->eq('account_id', $source->getId())
                ->load()
                ->getFirst();
            if($website){
                Application::$context->session->set('websiteId', $website->getId());
            }
        }
    }
}
