<?php
namespace Ixiangs\User;

use Toy\Web\Application;

class Listener
{

    static public function webOnStart($app, $argument)
    {
        $context = Application::$context;
        $data = $context->session->get('identity');
        if (!empty($data)) {
//            $identity = unserialize($data);
            $context->identity = new Identity(
                $data['id'], $data['username'], $data['level'], $data['roles'], $data['behaviors']
            );
        }
    }

    static public function webPostRoute($app, $argument)
    {
        $context = Application::$context;
        $identity = $context->identity;
        $router = $context->router;
        $response = $context->response;
        if ($router->domain->getName() == 'backend') {
            if ($identity) {

            } else {
                if (!($router->component == 'ixiangs_user' && $router->controller == 'passport')) {
                    $response->redirect($router->buildUrl('ixiangs_user/passport/login'));
                    $app->quit();
                }
            }
//			if($router->component != 'index'){
//				if(empty($oa)){
//					$resp->redirect($router->buildUrl('index/index/index'));
//					$app->quit();
//				}
//				if($oa->getLevel() != AccountModel::LEVEL_ADMINISTRATOR){
//					$app->getContext()->getSession()->set(
//						'errors',
//						$app->getContext()->locale->_('permission_denied')
//					);
//					$resp->redirect($router->buildUrl('index/index/index'));
//					$app->quit();
//				}
//			}
//		}elseif($router->domain->getName() == 'frontend'){
//			if(!($as->getComponent() == 'user' && $as->getController() == 'defend')){
//				if(empty($oa)){
//					$resp->redirect($router->buildUrl('user/defend/login'));
//					$app->quit();
//				}

            // $url = StringUtil::PascalCasingToDash($as->getComponent()->getName()).'/'.$as->getController().'/'.$as->getAction();
            // $code = BehaviorModel::find(array('url ='=>$url))->resetSelect()->select('code')->limit(1)->execute()->getFirstValue();
            // if(!empty($code) && !$oa->hasBehavior($code)){
            // $resp->redirect('/permissiondenied.html');
            // $app->quit();
            // }
//			}
        }
    }
}
