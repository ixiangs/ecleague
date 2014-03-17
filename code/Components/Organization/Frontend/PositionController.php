<?php
namespace Organization\Frontend;

use Organization\DepartmentModel, Organization\PositionModel, Organization\CompanyModel;

use Toy\Web\Controller, Toy\Web\Action\TemplateResult, Toy\Web\Action\RedirectResult;

class PositionController extends Controller{

	public function indexAction(){
		$pi = $this->request->getParameter("pageindex", 1);
		$count = PositionModel::find()->count()->execute()->getFirstValue();
		$models = PositionModel::find()
							->asc('parent_id')
							->execute()->getModelArray();	
		return new TemplateResult(array(
			'departments'=>DepartmentModel::find()->execute()->combineColumns('id', 'name'),
								// ->select(CompanyModel::TABLE_NAME.'.name AS company_name')
								// ->joinInner(CompanyModel::TABLE_NAME, CompanyModel::TABLE_NAME.'.id', DepartmentModel::TABLE_NAME.'.company_id')
								// ->execute()
								// ->getModelArray(),
			'models'=>$models,
			'total'=>$count,
			'pageIndex'=>$pi)
		);
	}
	
	public function addAction(){
		return $this->getEditTemplateResult(PositionModel::create());
	}	
	
	public function addPostAction(){			
		$m = PositionModel::create($this->request->getAllParameters());
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
		$m = PositionModel::load($this->request->getParameter('id'));
		return $this->getEditTemplateResult($m);
	}
	
	public function editPostAction(){
		$m = PositionModel::merge($this->request->getParameter('id'), $this->request->getAllParameters());
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
		$m = PositionModel::load($this->request->getParameter('id'));
		
		if(!$m){
			$this->session->set('errors', $this->languages->get('err_system'));
		}
		
		if(!$m->delete()){
			$this->session->set('errors', $this->languages->get('err_system'));		
		}		
		return new RedirectResult($this->router->buildUrl('index'));
	}		
	
	private function getEditTemplateResult($model){
		return new TemplateResult(array(
			'model'=>$model,
			'departments'=>DepartmentModel::getDepartmentOptionGroups(),
			'positions'=>PositionModel::find()->asc('parent_id')->execute()->combineColumns('id', 'chinese_name')
			),
			'organization/position/edit'
		);		
	}
}