<?php
namespace Organization\Frontend;

use Organization\DepartmentModel, Organization\PositionModel, Organization\CompanyModel, Organization\EmployeeModel;

use Toy\Web\Controller, Toy\Web\Action\DownloadResult, Toy\Web\Action\TemplateResult, Toy\Web\Action\RedirectResult, Toy\Web\Action\JsonResult;

class EmployeeController extends Controller{

	public function indexAction(){
		$pi = $this->request->getParameter("pageindex", 1);
		$count = EmployeeModel::find()->count()->execute()->getFirstValue();
		$models = EmployeeModel::find()->limit(PAGINATION_SIZE, ($pi-1)*PAGINATION_SIZE)->execute()->getModelArray();	
		return new TemplateResult(array(
			'models'=>$models,
			'departments'=>DepartmentModel::find()->execute()->combineColumns('id', 'name'),
			'positions'=>PositionModel::find()->execute()->combineColumns('id', 'chinese_name'),
			'total'=>$count,
			'pageIndex'=>$pi)
		);
	}
	
	public function addAction(){
		return $this->getEditTemplateResult(EmployeeModel::create());
	}	
	
	public function addPostAction(){			
		$m = EmployeeModel::create($this->request->getAllParameters());
		$vr = $m->validate();
		if($vr !== true){
			$this->session->set('errors', $this->languages->get('err_input_invalid'));
			return $this->getEditTemplateResult($m);
		}
		
		if(!$m->insert()){
			$this->session->set('errors', $this->languages->get('err_system'));
			return $this->getEditTemplateResult($m);		
		}
		
		return new RedirectResult($this->router->buildUrl('index'));
	}	

	public function editAction(){
		$m = EmployeeModel::load($this->request->getParameter('id'));
		return $this->getEditTemplateResult($m);
	}
	
	public function editPostAction(){
		$m = EmployeeModel::merge($this->request->getParameter('id'), $this->request->getAllParameters());
		if(!$m){
			$this->session->set('errors', $this->languages->get('err_system'));
			return $this->getEditTemplateResult($m);	
		}		

		$vr = $m->validate();
		if($vr !== true){
			$this->session->set('errors', $this->languages->get('err_input_invalid'));
			return $this->getEditTemplateResult($m);
		}
		
		if(!$m->update()){
			$this->session->set('errors', $this->languages->get('err_system'));
			return $this->getEditTemplateResult($m);		
		}
		
		$to = $this->router->buildUrl('index');
		return new RedirectResult(\Toy\Joy::history()->find($to, $to));
	}
	
	public function deleteAction(){
		$m = EmployeeModel::load($this->request->getParameter('id'));
		if(!$m){
			$this->session->set('errors', $this->languages->get('err_system'));
		}
		if(!$m->delete()){
			$this->session->set('errors', $this->languages->get('err_system'));		
		}		
		return new RedirectResult($this->router->buildUrl('index', array('cid'=>$m->getCompanyId())));
	}		
	
	public function exportAction($format){
		switch($format){
			case 'foxmail':
				return $this->getFoxmailContacts();
			case 'android':
				return $this->getAndoridContacts();
			case 'iphone':
				return $this->getIPhoneContacts();
		}
	}
	
	private function getFoxmailContacts(){
		$employees = EmployeeModel::find()->execute()->getModelArray();
		$positions = PositionModel::find()->execute()->combineColumns('id', 'chinese_name');		
		$lines = array('名,姓,姓名,电子邮件地址,手机,职位,办公电话1');
		foreach($employees as $employee){
			$lines[] = $employee->getChineseName().','.$employee->getEnglishName().','.
									$employee->getChineseName().' '.
									$employee->getEnglishName().','.
									$employee->getEmail().','.
									$employee->getMobile().','.
									$positions[$employee->getPositionId()].','.
									$employee->getPhone();
		}
		$content = implode("\r\n", $lines);
		if(substr($this->request->getBrowserLanguage(), 0, 2) == 'zh'){
			$content = iconv('utf-8', 'gbk', $content);
		}		
		return new DownloadResult('comex contacts for foxmail.csv', $content);										
	}
	
	private function getAndoridContacts(){
		$employees = EmployeeModel::find()->execute()->getModelArray();
		$positions = PositionModel::find()->execute()->combineColumns('id', 'chinese_name');
		$lines = array();
		foreach($employees as $employee){
			$lines[] = 'BEGIN:VCARD';
			$lines[] = 'VERSION:3.0';
			$lines[] = 'FN;CHARSET=UTF-8:'.$employee->getChineseName().' '.$employee->getEnglishName();
			$lines[] = 'N;CHARSET=UTF-8:'.$employee->getChineseName().';'.$employee->getEnglishName();
			$lines[] = 'CATEGORIES:Comex';
			$mobiles = explode('/', $employee->getMobile());
			foreach($mobiles as $mobile){
				$lines[] = 'TEL;TYPE=CELL:'.$mobile;
			}
			if($employee->getPhone()){
				$lines[] = 'TEL;TYPE=WORK:'.str_replace('-', ',', $employee->getPhone());
			}
			$lines[] = 'EMAIL;TYPE=WORK:'.$employee->getEmail();
			$lines[] = 'ORG;TYPE=WORK:'.$positions[$employee->getPositionId()];
			$lines[] = 'END:VCARD';
		}
		$content = implode($this->request->isWindows()? "\r\n": "\n", $lines);
		if(substr($this->request->getBrowserLanguage(), 0, 2) == 'zh'){
			$content = iconv('utf-8', 'gbk', $content);
		}		
		return new DownloadResult('Comex contacts for android.vcf', $content);
	}

	private function getIPhoneContacts(){
		$r = $this->getAndoridContacts();
		$r->setContent(iconv('gbk', 'utf-8', $r->getContent()));
		$r->setFilename('Comex contacts for iphone.vcf');
		return $r;
	}
	
	private function getEditTemplateResult($model){
		return new TemplateResult(array(
			'companies'=>CompanyModel::find()->execute()->combineColumns('id', 'name'),
			'departments'=>DepartmentModel::find()->execute()->combineColumns('id', 'name'),
			'employees'=>EmployeeModel::find()->execute()->combineColumns('id', 'chinese_name'),
			'positions'=>PositionModel::find()->execute()->combineColumns('id', 'chinese_name'),
			'model'=>$model),
			'organization/employee/edit'
		);		
	}
	
	private function getBossOptions(){
		$departments = DepartmentModel::find()->execute()->getModelArray();
		$employeess = EmployeeModel::find()->execute()->getModelArray();
		
		$result = array();
		foreach($departments as $d){
			$item = array('group'=>$d->getName(), 'options'=>array());
			foreach($employeess as $e){
				if($e->getDepartmentId() == $d->getId()){
					$item['options'][$e->getId()] = $e->getName();
				}
			}
		}
		return $result;
	}
}