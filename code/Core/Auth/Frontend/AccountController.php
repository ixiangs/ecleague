<?php
namespace User\Frontend;

use Toy\Web\Controller, Toy\Web\Action\TemplateResult, Toy\Web\Action\RedirectResult;
use User\AccountModel, User\RoleModel;

class AccountController extends Controller{

	public function changePasswordAction(){
		return new TemplateResult();
	}
	
	public function changePasswordPostAction(){
		$oldpwd = $this->request->getParameter('oldpassword');
		$newpwd = $this->request->getParameter('newpassword');
		$oa = $this->getContext()->getItem('onlineAccount');
		$r = AccountModel::modifyPassword($oa->getId(), $oldpwd, $newpwd);
		if($r === true){
			$this->session->set('success', $this->languages->get('success_operation'));	
			return new RedirectResult();
		}else{
			switch($r){
				case AccountModel::ERROR_PASSWORD:
					$this->session->set('errors', $this->languages->get('err_password'));
					break;
				case AccountModel::ERROR_UNKNOW:
					$this->session->set('errors', $this->languages->get('err_system'));
					break;
			}
			return new TemplateResult();
		}
		
	}	
}