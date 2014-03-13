<?php
namespace Index\Backend;

use Toys\Framework\Controller, Toys\Framework\Action\TemplateResult;

class IndexController extends Controller{

	public function indexAction(){
		return new TemplateResult();
	}
}