<?php
namespace Core\Auth;

use Core\Auth\Model\AccountModel;

class Listener{
	
	static public function applicationOnStart($app, $argument){
		$oa = $app->getContext()->session->get('identity');
		if(!empty($oa)){
			$oa = unserialize($oa);
			$app->getContext()->identity = new Identity(
				$oa['id'], $oa['username'], $oa['level'], $oa['roles'], $oa['behaviors']
			);
		}
	}

	static public function applicationPostRoute($app, $argument){
		$oa = $app->getContext()->identity;
        $router = $app->getContext()->router;
		$resp = $app->getContext()->response;
		if($router->domain->getName() == 'backend'){
			if($router->component != 'index'){
				if(empty($oa)){
					$resp->redirect($router->buildUrl('index/index/index'));
					$app->quit();
				}
				if($oa->getLevel() != AccountModel::LEVEL_ADMINISTRATOR){
					$app->getContext()->getSession()->set(
						'errors',
						$app->getContext()->locale->_('permission_denied')
					);
					$resp->redirect($router->buildUrl('index/index/index'));
					$app->quit();
				}
			}
		}elseif($router->domain->getName() == 'frontend'){
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
