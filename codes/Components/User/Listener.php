<?php
namespace Components\User;

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
                $data['id'], $data['username'], $data['level'], $data['roles'], $data['behaviors']
            );
            $context->identity = $identity;
            Template::addHelper('identity', $identity);
        }
    }

    static public function webPostRoute($app, $argument)
    {
//        $context = Application::$context;
//        $identity = $context->identity;
//        $router = $context->router;
//        $response = $context->response;
//
//        if ($router->domain->getName() == 'backend') {
//            if ($identity) {
////                $uri = $context->request->getUri();
////                $behaviors = BehaviorModel::find()->load();
////                foreach ($behaviors as $behavior) {
////                    $url = $behavior->getUrl();
////                    if ($url) {
////                        if ($url[0] == '/' && substr($url, -1) == '/') {
////                            if (preg_match($url, $uri)) {
////                                if (!$identity->hasBehavior($behavior->getCode())) {
////                                    $response->redirect('/permissiondenied.html');
////                                    $app->quit();
////                                }
////                            }
////                        } elseif ($url == $uri) {
////                            if (!$identity->hasBehavior($behavior->getCode())) {
////                                $response->redirect('/permissiondenied.html');
////                                $app->quit();
////                            }
////                        }
////                    }
////                }
//            } else {
//                if (!($router->component == 'ixiangs_user' && $router->controller == 'passport')) {
//                    $response->redirect($router->buildUrl('ixiangs_user/passport/login'));
//                    $app->quit();
//                }
//            }
//        }
    }
}
