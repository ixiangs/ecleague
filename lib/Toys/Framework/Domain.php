<?php
namespace Toys\Framework;
use Toys\Util\StringUtil;

class Domain{
	
	private $_name = '';
	private $_indexUrl = '';
	private $_indexHandler = '';
	// private $_indexController = '';
	private $_namespace = '';
	// private $_controllerPrefix = '';
	private $_default = false;
	// private $_defaultController = null;
	private $_actions = array();
	
	public function __construct($name, $namespace, $indexUrl, $indexHandler, $default){
		$this->_name = $name;
		$this->_indexUrl = $indexUrl;
		$this->_indexHandler = $indexHandler;
		$this->_namespace = $namespace;
		$this->_default = $default;
	}
	
	public function addController($url, $ctrl){
		$rc = new \ReflectionClass($ctrl);
		$ms = $rc->getMethods();
		foreach($ms as $m){
			if($m->isPublic() && StringUtil::endsWith($m->getName(), 'Action')){
				$n = substr($m->getName(), 0, -6);
				$n = str_replace(array('Get', 'Post', 'Put', 'Delete', 'Head', 'Options'), '', $n);
				$aurl = $url .'/'. StringUtil::PascalCasingToDash($n);
				$this->_actions[$aurl] = array('handler'=>$ctrl.':'.$n);
			}
		}
		return $this;
	}

	public function getName(){
		return $this->_name;
	}
	
	public function getIndexUrl(){
		return $this->_indexUrl;
	}
	
	public function getIndexHandler(){
		return $this->_indexHandler;
	}
	
	public function getNamespace(){
		return $this->_namespace;
	}
	
	public function getActions(){
		return $this->_actions;
	}

	public function getDefault(){
		return $this->_default;
	}
}
