<?php
namespace Index\Frontend;

use Toy\Web\Controller, Toy\Web\Action\TemplateResult;

class IndexController extends Controller{

	public function indexAction(){
		return new TemplateResult();
	}
}