<?php
namespace User;
use Toys\Util\StringUtil;

class Listener{
	
	public static function applicationOnStart($app, $argument){
		$oa = $app->getContext()->getSession()->get('onlineAccount');
		if(!empty($oa)){
			$oa = unserialize($oa);
			$app->getContext()->setItem('onlineAccount', new OnlineAccount(
				$oa['id'], $oa['username'], $oa['level'], $oa['roles'], $oa['behaviors']		
			));
		}
	}

	public static function applicationPostRoute($app, $argument){
		$as = $app->getContext()->getObjective();
		$oa = $app->getContext()->getItem('onlineAccount');
		$domain = $app->getContext()->getDomain();
		$resp = $app->getContext()->getResponse();
		$router = $app->getContext()->getRouter();
		if($domain->getName() == 'backend'){
			if(!($as->getComponent() == 'user' && $as->getController() == 'defend')){
				if(empty($oa)){
					$resp->redirect($router->buildUrl('user/defend/login'));
					$app->quit();
				}			
				if($oa->getLevel() != AccountModel::LEVEL_ADMINISTRATOR){
					$app->getContext()->getSession()->set(
						'errors',
						$app->getContext()->getLanguage()->get('permission_denied')
					);
					$resp->redirect($router->buildUrl('user/defend/login'));
					$app->quit();				
				}
			}
		}elseif($domain->getName() == 'frontend'){
			if(!($as->getComponent() == 'user' && $as->getController() == 'defend')){
				if(empty($oa)){
					$resp->redirect($router->buildUrl('user/defend/login'));
					$app->quit();
				}			
				
				// $url = StringUtil::PascalCasingToDash($as->getComponent()->getName()).'/'.$as->getController().'/'.$as->getAction();
				// $code = BehaviorModel::find(array('url ='=>$url))->resetSelect()->select('code')->limit(1)->execute()->getFirstValue();
				// if(!empty($code) && !$oa->hasBehavior($code)){
					// $resp->redirect('/permissiondenied.html');
					// $app->quit();						
				// }
			}		
		}
	}
}
