<?php
namespace Core\Index\Backend;

use Toy\Web\Controller;
use Toy\Web\Result;

class IndexController extends Controller{

	public function indexAction(){
		return Result::templateResult();
	}
}