<?php
namespace Index\Backend;

use Toys\Web\Controller, Toys\Web\Action\TemplateResult;

class IndexController extends Controller{

	public function indexAction(){
		return new TemplateResult();
	}
}