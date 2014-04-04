<?php
namespace System\Backend;

use Toy\Web\Controller, Toy\Web\Action\TemplateResult, Toy\Web\Action\RedirectResult;

class AreaController extends Controller{

	public function indexAction(){
		$pi = $this->request->getParameter("pageindex", 1);
		$count = AreaModel::find()->count()->execute()->getFirstValue();
		$areas = AreaModel::find()->asc("level")->asc("id")->limit(PAGINATION_SIZE, ($pi-1)*PAGINATION_SIZE)->load();
		return new TemplateResult(array(
			'areas'=>$areas,
			'total'=>$count,
			'pageIndex'=>$pi)
		);
	}
	
	public function addAction(){
		return new TemplateResult(
			array('model'=>AreaModel::create()),
			'system/area/edit'
		);
	}	
	
	public function addPostAction(){
		$m = AreaModel::create($this->request->getAllParameters());
		$vr = $m->validate();
		if($vr !== true){
			$this->session->set('errors', $this->languages->get('err_input_invalid'));
			return new TemplateResult(array(
				'model'=>$m)
			);
		}
		
		if(!$m->insert()){
			$this->session->set('errors', $this->languages->get('err_system'));
			return new TemplateResult(array(
				'model'=>$m)
			);			
		}
		
		return new RedirectResult($this->router->buildUrl('index'));
	}	

	public function editAction($id){
		$m = AreaModel::load($id);
		return new TemplateResult(array(
			'model'=>$m)
		);
	}
	
	public function editPostAction(){
		$m = AreaModel::merge($this->request->getParameter('id'), $this->request->getAllParameters());
		$vr = $m->validate();
		if($vr !== true){
			$this->session->set('errors', $this->languages->get('err_input_invalid'));
			return new TemplateResult(array(
				'model'=>$m)
			);
		}
		
		if(!$m->update()){
			$this->session->set('errors', $this->languages->get('err_system'));
			return new TemplateResult(array(
				'model'=>$m)
			);			
		}
		
		$to = $this->router->buildUrl('index');
		return new RedirectResult(\Toy\Joy::history()->find($to, $to));
	}
}