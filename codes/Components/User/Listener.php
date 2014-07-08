<?php
namespace Components\User;

use Components\User\Models\BehaviorModel;
use Toy\Web\Application;
use Toy\Web\Template;

class Listener
{

    static public function webOnStart($app, $argument)
    {
        $context = Application::$context;
        $data = $context->session->get('identity');
        if (!empty($data)) {
            $identity = new Identity(
                $data['id'],
                $data['username'],
                $data['domains'],
                $data['roles'],
                $data['behaviors'],
                $data['items']
            );
            $context->identity = $identity;
            Template::addHelper('identity', $identity);
        } else {
            $context->identity = Identity::guest();
        }
    }

    static public function webPostRoute($app, $argument)
    {
        $context = Application::$context;
        $identity = $context->identity;
        $router = $context->router;
        $response = $context->response;
        $curDomain = $router->domain;

        if (!$curDomain->getAuthorizationRequired()) {
            return;
        }


        if ($curDomain->getLoginUrl() == $router->component . '/' . $router->controller . '/' . $router->action) {
            return;
        }

        if (!$identity->isAuthenticated() ||
            !$identity->hasDomain($router->domain->getName())
        ) {
            $response->redirect($router->buildUrl($curDomain->getLoginUrl()));
            $app->quit();
            return;
        }

        $uri = $context->request->getUri();
        $behaviors = BehaviorModel::find()->load();
        foreach ($behaviors as $behavior) {
            $url = $behavior->getUrl();
            if ($url) {
                if ($url[0] == '/' && substr($url, -1) == '/') {
                    if (preg_match($url, $uri)) {
                        if (!$identity->hasBehavior($behavior->getCode())) {
                            $response->redirect('/permissiondenied.html');
                            $app->quit();
                            return;
                        }
                    }
                } elseif ($url == $uri) {
                    if (!$identity->hasBehavior($behavior->getCode())) {
                        $response->redirect('/permissiondenied.html');
                        $app->quit();
                        return;
                    }
                }
            }
        }

    }
}
