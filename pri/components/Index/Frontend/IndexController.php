<?php
namespace Index\Frontend;

use Toys\Framework\Controller, Toys\Framework\Action\TemplateResult;

class IndexController extends Controller{

	public function indexAction(){
		return new TemplateResult();
	}
}