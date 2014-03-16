<?php
namespace Organization\Frontend;

use Organization\DepartmentModel;
use Organization\CompanyModel;

use Toy\Web\Controller, Toy\Web\Action\TemplateResult, Toy\Web\Action\RedirectResult;

class DepartmentController extends Controller{

	public function indexAction(){
		$pi = $this->request->getParameter("pageindex", 1);
		$count = DepartmentModel::find()->count()->execute()->getFirstValue();
		$models = DepartmentModel::find()
								->select(CompanyModel::TABLE_NAME.'.name AS company_name')
								->joinInner(CompanyModel::TABLE_NAME, CompanyModel::TABLE_NAME.'.id', DepartmentModel::TABLE_NAME.'.company_id')
								->limit(PAGINATION_SIZE, ($pi-1)*PAGINATION_SIZE)->execute()->getModelArray();
		return new TemplateResult(array(
			'models'=>$models,
			'total'=>$count,
			'pageIndex'=>$pi)
		);
	}
	
	public function addAction(){
		return $this->getEditTemplateResult(DepartmentModel::create());
	}	
	
	public function addPostAction(){			
		$m = DepartmentModel::create($this->request->getAllParameters());
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
		$m = DepartmentModel::load($this->request->getParameter('id'));
		return $this->getEditTemplateResult($m);
	}
	
	public function editPostAction(){
		$m = DepartmentModel::merge($this->request->getParameter('id'), $this->request->getAllParameters());
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
		
		$to = $this->router->buildUrl('index', array('cid'=>$m->getCompanyId()));
		return new RedirectResult(\Toy\Joy::history()->find($to, $to));
	}
	
	public function deleteAction(){
		$m = DepartmentModel::load($this->request->getParameter('id'));
		
		if(!$m){
			$this->session->set('errors', $this->languages->get('err_system'));
		}
		
		if(!$m->delete()){
			$this->session->set('errors', $this->languages->get('err_system'));		
		}		
		return new RedirectResult($this->router->buildUrl('index', array('cid'=>$m->getCompanyId())));
	}		
	
	private function getEditTemplateResult($model){
		return new TemplateResult(array(
			'model'=>$model,
			'companies'=>CompanyModel::find()->execute()->combineColumns('id', 'name')),
			'organization/department/edit'
		);		
	}
}