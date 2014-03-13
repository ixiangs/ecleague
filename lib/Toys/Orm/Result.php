<?php
namespace Toys\Orm;

use Toys\Collection\ArrayList;

class Result extends \Toys\Data\Result {
	
	private $_entity = null;
	
	public function __construct($entity, $source){
		parent::__construct($source);
		$this->_entity = $entity;
	}
	
	public function getFirstModel() {
		$row = $this -> getFirstRow();
		$mc = $this->_entity->getModelClass();
		if ($row) {
			$result = new $mc;
			$result -> fillRow($row);
			return $result;
		}
		return null;
	}

	public function getModelArray() {
		$result = array();
		$mc = $this->_entity->getModelClass();
		foreach ($this->rows as $row) {
			$m = new $mc;
			$m -> fillRow($row);
			$result[] = $m;
		}
		return $result;
	}

	public function getModelList() {
		return new ArrayList($this -> getModelArray());
	}

}
