<?php
namespace Organization\Frontend;

use Organization\CompanyModel;

use Toys\Web\Controller, Toys\Web\Action\TemplateResult, Toys\Web\Action\RedirectResult;

class CompanyController extends Controller{

	public function indexAction(){
		$pi = $this->request->getParameter("pageindex", 1);
		$count = CompanyModel::find()->count()->execute()->getFirstValue();
		$models = CompanyModel::find()->limit(PAGINATION_SIZE, ($pi-1)*PAGINATION_SIZE)->execute()->getModelArray();	
		return new TemplateResult(array(
			'models'=>$models,
			'total'=>$count,
			'pageIndex'=>$pi)
		);
	}
	
	public function addAction(){
		return $this->getEditTemplateResult(CompanyModel::create());
	}	
	
	public function addPostAction(){
		$m = CompanyModel::create($this->request->getAllParameters());
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

	public function editAction($id){
		$m = CompanyModel::load($id);
		return $this->getEditTemplateResult($m);
	}
	
	public function editPostAction(){
		$m = CompanyModel::merge($this->request->getParameter('id'), $this->request->getAllParameters());
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
		return new RedirectResult(\Toys\Joy::history()->find($to, $to));
	}
	
	private function getEditTemplateResult($model){
		return new TemplateResult(
			array('model'=>$model),
			'organization/company/edit'
		);			
	}
}