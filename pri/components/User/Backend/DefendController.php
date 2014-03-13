<?php
namespace User\Backend;

use Toys\Framework\Controller, Toys\Framework\Action\TemplateResult, Toys\Framework\Action\RedirectResult;
use User\AccountModel;

class DefendController extends Controller{

	public function loginAction(){
		return new TemplateResult();
	}

	public function loginPostAction(){
		list($r, $ol) = AccountModel::login($this->request->getParameter('username'), $this->request->getParameter('password'));
		if($r === true){
			if($ol->getLevel()!= AccountModel::LEVEL_ADMINISTRATOR){
				$this->session->set('errors', $this->languages->get('permission_denied'));
				return new TemplateResult();
			}
			$this->session->set('onlineAccount', serialize(array('id'=>$ol->getId(), 
				'username'=>$ol->getUsername(),
				'level'=>$ol->getLevel(),
				'roles'=>$ol->getRoles(),
				'behaviors'=>$ol->getBehaviors())
			));
			return new RedirectResult($this->router->buildUrl('index/index/index'));			
		}else{
			switch($r){
				case AccountModel::ERROR_NOT_FOUND:
				case AccountModel::ERROR_PASSWORD:
					$this->session->set('errors', $this->languages->get('err_login'));
					break;
				case AccountModel::ERROR_NONACTIVATED:
					$this->session->set('errors', $this->languages->get('err_account_nonactivated'));
					break;
				case AccountModel::ERROR_DISABLED:
					$this->session->set('errors', $this->languages->get('err_account_disabled'));
					break;
			}
			return new TemplateResult();
		}
	}

	public function logoutAction(){
		$this->session->abort();
		header("Location: http://".$_SERVER['HTTP_HOST'].$this->router->buildUrl('user/guard/login'));
		exit();
	}
}