<?php
namespace User\Backend;

use Toy\Web\Controller, Toy\Web\Action\TemplateResult, Toy\Web\Action\RedirectResult;
use User\RoleModel, User\BehaviorModel;

class RoleController extends Controller{

	public function indexAction(){
		$pi = $this->request->getParameter("pageindex", 1);
		$count = RoleModel::find()->count()->execute()->getFirstValue();
		$models = RoleModel::find()->limit(PAGINATION_SIZE, ($pi-1)*PAGINATION_SIZE)->execute()->getModelArray();
		return new TemplateResult(array(
			'models'=>$models,
			'behaviors' => BehaviorModel::find()->execute()->combineColumns('id', 'code'),
			'total'=>$count,
			'pageIndex'=>$pi)
		);
	}
	
	public function addAction(){
		return $this->getEditTemplateReult(RoleModel::create());
	}	
	
	public function addPostAction(){
		$lang = $this->languages;
		$m = RoleModel::create($this->request->getAllParameters());
		if(RoleModel::checkCode($m->getCode())){
			$this->session->set('errors', $lang->format($lang['err_code_exists'], $m->getCode()));
			return $this->getEditTemplateReult($m);	
		}
		
		$vr = $m->validate();
		if($vr !== true){
			$this->session->set('errors', $this->languages->get('err_input_invalid'));
			return $this->getEditTemplateReult($m);
		}
		
		if(!$m->insert()){
			$this->session->set('errors', $this->languages->get('err_system'));
			return $this->getEditTemplateReult($m);;			
		}
		
		return new RedirectResult($this->router->buildUrl('index'));
	}	

	public function editAction($id){
		$m = RoleModel::load($id);
		return $this->getEditTemplateReult($m);
	}
	
	public function editPostAction(){
		$m = RoleModel::merge($this->request->getParameter('id'), $this->request->getAllParameters());
		$m->setRoleIds($this->request->getParameter('behavior_ids', array()));
		$vr = $m->validate();
		if($vr !== true){
			$this->session->set('errors', $this->languages->get('err_input_invalid'));
			return $this->getEditTemplateReult($m);
		}
		
		if(!$m->update()){
			$this->session->set('errors', $this->languages->get('err_system'));
			return $this->getEditTemplateReult($m);
		}
		
		return new RedirectResult($this->router->buildUrl('index'));
	}
	
	public function deleteAction($id){
		$m = RoleModel::load($id);
		
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
	
	private function getEditTemplateReult($model){
		return new TemplateResult(
			array(
				'model'=>$model, 
				'behaviors'=>BehaviorModel::find()->asc('code')->execute()->combineColumns('id', 'label')),
			'comex/user/role/edit'
		);		
	}
}