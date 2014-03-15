<?php
namespace User\Backend;

use Toys\Web\Controller, Toys\Web\Action\TemplateResult, Toys\Web\Action\RedirectResult;
use User\BehaviorModel;

class BehaviorController extends Controller{

	public function indexAction(){
		$pi = $this->request->getParameter("pageindex", 1);
		$count = BehaviorModel::find()->count()->execute()->getFirstValue();
		$models = BehaviorModel::find()->limit(PAGINATION_SIZE, ($pi-1)*PAGINATION_SIZE)->execute()->getModelArray();	
		return new TemplateResult(array(
			'models'=>$models,
			'total'=>$count,
			'pageIndex'=>$pi)
		);
	}
	
	public function addAction(){
		return $this->getEditTemplateResult(BehaviorModel::create());
	}	
	
	public function addPostAction(){
		$lang = $this->languages;
		$m = BehaviorModel::create($this->request->getAllParameters());
		if(BehaviorModel::checkCode($m->getCode())){
			$this->session->set('errors', $this->languages->format($lang['err_code_exists'], $m->getCode()));
			return $this->getEditTemplateResult($m);			
		}
		
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
		$m = BehaviorModel::load($id);
		return new TemplateResult(array(
			'model'=>$m)
		);
	}
	
	public function editPostAction(){
		$m = BehaviorModel::merge($this->request->getParameter('id'), $this->request->getAllParameters());
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
		$m = BehaviorModel::load($id);
		
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
	
	private function getEditTemplateResult($model){
		return new TemplateResult(
			array('model'=>$model),
			'comex/user/behavior/edit'
		);
	}	
}