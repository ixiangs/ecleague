<?php
namespace Toys\Orm;

use Toys\Joy;
use TOys\Data\Db;
use Toys\Data\Query;

class Finder extends Query{

	private $_entity = null;
	
	public function __construct($entity){
		$this->_entity = $entity;
	}

	public function getEntity(){
		return $this->_entity;
	}

	// public function setModelClass($value){
		// $this->_modelClass = $value;
		// return $this;
	// }

	public function execute($db = null){
		$cdb = $db? $db: Db::current();
		return new Result($this->_entity, $cdb->query($this)->rows);
	} 
}