<?php
namespace Components\Content;


use Components\Content\Models\PublisherModel;
use Toy\Web\Application;

class Listener
{

    static public function authOnLogin($source, $argument)
    {
        $query = PublisherModel::find()
            ->eq('account_id', $argument->getId())
            ->load();
        if (count($query) > 0) {
            $publisher = $query->getFirst();
            Application::$context->session->set('publisherId', $publisher->getId());
        }
    }
}
