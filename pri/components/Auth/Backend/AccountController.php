<?php
namespace User\Backend;

use Toys\Util\FileUtil;
use Toys\Web\Controller, Toys\Web\Action\TemplateResult, Toys\Web\Action\RedirectResult,Toys\Web\Action\DownloadResult;
use User\AccountModel, User\RoleModel, User\ProfileModel;

class AccountController extends Controller{

	public function indexAction(){
		$pi = $this->request->getParameter("pageindex", 1);
		$count = AccountModel::find()->count()->execute()->getFirstValue();
		$models = AccountModel::find()
							->asc('id')
							->limit(PAGINATION_SIZE, ($pi-1)*PAGINATION_SIZE)
							->execute()->getModelArray();	
		return new TemplateResult(array(
			'models'=>$models,
			'roles' => RoleModel::find()->execute()->combineColumns('id', 'code'),
			'total'=>$count,
			'pageIndex'=>$pi)
		);
	}
	
	public function addAction(){
		return $this->getEditTemplateResult(AccountModel::create());
	}	
	
	public function addPostAction(){
		$m = AccountModel::create($this->request->getAllParameters());
		$r = $m->register();
		if($r === true){
			return new RedirectResult($this->router->buildUrl('index'));
		}
		
		$lang = $this->languages;
		switch($r){
			case AccountModel::ERROR_ACCOUNT_REPEATED:
				$this->session->set('errors', $lang->format($lang['err_account_exists'], $m->getUsername()));
				break;
			case AccountModel::ERROR_UNKNOW:
				$this->session->set('errors', $lang->get('err_system'));
				break;
		}
		return $this->getEditTemplateResult($m);		
	}	

	public function editAction($id){
		$m = AccountModel::load($id);
		return $this->getEditTemplateResult($m);
	}
	
	public function editPostAction(){
		$m = AccountModel::merge($this->request->getParameter('id'), $this->request->getAllParameters());
		$m->setRoleIds($this->request->getParameter('role_ids', array()));
		$vr = $m->validate();
		if($vr !== true){
			$this->session->set('errors', $this->languages->get('err_input_invalid'));
			return $this->getEditTemplateResult($m);
		}
		
		if(!$m->update()){
			$this->session->set('errors', $this->languages->get('err_system'));
			return $this->getEditTemplateResult($m);			
		}
		
		return new RedirectResult($this->router->buildUrl('index'));
	}
	
	public function deleteAction($id){
		$m = AccountModel::load($id);
		
		if(!$m){
			$this->session->set('errors', $this->languages->get('err_system'));
			return new RedirectResult($this->router->buildUrl('index'));
		}
		
		if(!$m->delete()){
			$this->session->set('errors', $this->languages->get('err_system'));
			return new RedirectResult($this->router->buildUrl('index'));			
		}		
		return new RedirectResult($this->router->buildUrl('index'));
	}
	
	public function profileAction($id){
		return $this->getProfileTemplateResult(ProfileModel::load($id));
	}
	
	public function profilePostAction(){
		$m = ProfileModel::merge($this->request->getParameter('account_id'), $this->request->getAllParameters());
		if(!$m){
			$this->session->set('errors', $this->languages->get('err_system'));
			return $this->getProfileTemplateResult($m);	
		}		

		$vr = $m->validate();
		if($vr !== true){
			$this->session->set('errors', $this->languages->get('err_input_invalid'));
			return $this->getProfileTemplateResult($m);
		}
		
		if(!$m->update()){
			$this->session->set('errors', $this->languages->get('err_system'));
			return $this->getProfileTemplateResult($m);		
		}
		
		$to = $this->router->buildUrl('index');
		return new RedirectResult(\Toys\Joy::history()->find($to, $to));
	}	
	
	public function exportContactsAction(){
		$profiles = ProfileModel::find()
									->andFilter('chinese_name notnull')
									->execute()->getModelArray();	
		
		//foxmail format	
		$lines = array('名,姓,姓名,电子邮件地址,手机,办公电话1');
		foreach($profiles as $profile){
			$lines[] = $profile->getChineseName().','.$profile->getEnglishName().','.
									$profile->getChineseName().' '.
									$profile->getEnglishName().','.
									$profile->getWorkEmail().','.
									$profile->getMobile().','.
									$profile->getWorkPhone();
		}
		$foxmail = implode("\r\n", $lines);
		if(substr($this->request->getBrowserLanguage(), 0, 2) == 'zh'){
			$foxmail = iconv('utf-8', 'gbk', $foxmail);
		}		
		
		//android format
		$lines = array();
		foreach($profiles as $profile){
			$lines[] = 'BEGIN:VCARD';
			$lines[] = 'VERSION:3.0';
			$lines[] = 'FN;CHARSET=UTF-8:'.$profile->getChineseName().' '.$profile->getEnglishName();
			$lines[] = 'N;CHARSET=UTF-8:'.$profile->getChineseName().';'.$profile->getEnglishName();
			$lines[] = 'CATEGORIES:Comex';
			$mobiles = explode('/', $profile->getMobile());
			foreach($mobiles as $mobile){
				$lines[] = 'TEL;TYPE=CELL:'.$mobile;
			}
			if($profile->getWorkPhone()){
				$lines[] = 'TEL;TYPE=WORK:'.str_replace('-', ',', $profile->getPhone());
			}
			$lines[] = 'EMAIL;TYPE=WORK:'.$profile->getWorkEmail();
			// $lines[] = 'ORG;TYPE=WORK:'.$positions[$profile->getPositionId()];
			$lines[] = 'END:VCARD';
		}
		$android = implode($this->request->isWindows()? "\r\n": "\n", $lines);
		if(substr($this->request->getBrowserLanguage(), 0, 2) == 'zh'){
			$android = iconv('utf-8', 'gbk', $android);
		}		

		//iphone format
		$iphone = implode($this->request->isWindows()? "\r\n": "\n", $lines);		
		
		FileUtil::writeFile(TEMP_PATH.'contacts for foxmail.csv', $foxmail);
		FileUtil::writeFile(TEMP_PATH.'contacts for android.vcf', $android);
		FileUtil::writeFile(TEMP_PATH.'contacts for iphone.vcf', $iphone);
		
		$zip = new \ZipArchive();
		$res = $zip->open(TEMP_PATH.'contacts.zip', \ZipArchive::CREATE);
		if ($res === TRUE) {
	    $zip->addFile(TEMP_PATH.'contacts for foxmail.csv', 'contacts for foxmail.csv');
			$zip->addFile(TEMP_PATH.'contacts for android.vcf', 'contacts for android.vcf');
			$zip->addFile(TEMP_PATH.'contacts for iphone.vcf', 'contacts for iphone.vcf');
	    $zip->close();
			return new DownloadResult('contacts.zip', FileUtil::readFile(TEMP_PATH.'contacts.zip'));
		} else {
			$this->session->set('errors', 'export failure');
			return new RedirectResult('index');
		}		
	}

	private function getProfileTemplateResult($model){
		return new TemplateResult(
			array('model'=>$model),
			'user/account/profile'
		);		
	}
	
	private function getEditTemplateResult($model){
		return new TemplateResult(
			array(
				'model'=>$model,
				'roles' => RoleModel::find()->execute()->combineColumns('id', 'label')
			),
			'user/account/edit'
		);		
	}
}