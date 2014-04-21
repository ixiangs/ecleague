<?php
namespace Core\Admin;

use Toy\Web\Application;

class Listener
{
    static public function webOnStart($app, $argument)
    {
        $oa = Application::$context->session->get('administrator');
        if (!empty($oa)) {
            $oa = unserialize($oa);
            $id = new \stdClass();
            $id->id = $oa['id'];
            $id->username = $oa['username'];
            Application::$context->administrator = $id;
        }
    }

    static public function webPostRoute($app, $argument)
    {
        $oa = Application::$context->administrator;
        $router = Application::$context->router;
        $resp = Application::$context->response;
        if ($router->domain->getName() == 'backend') {
            if (empty($oa)) {
                if ($router->component == 'admin' && $router->controller == 'account') {
                } else {
                    $resp->redirect($router->buildUrl('admin/account/login'));
                    $app->quit();
                }
            }
        }

    }

}
