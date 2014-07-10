<?php
namespace Components\Realty;

use Components\Realty\Models\UptownModel;
use Toy\Web\Application;

class Listener
{

    static public function authOnLogin($source, $argument)
    {
        $query = UptownModel::find()
            ->eq('account_id', $argument->getId())
            ->load();
        if (count($query) > 0) {
            $uptown = $query->getFirst();
            Application::$context->session->set('uptownId', $uptown->getId());
        }
    }
}
