<?php
namespace Toys\Data;

class Result {

	public $rows = null;
	private $_isEmpty = false;
	public function __construct(array $source) {
		$this -> rows = $source;
		$this->_isEmpty = empty($this->rows) || count($this->rows) == 0;
	}
	
	public function isEmpty(){
		return $this->_isEmpty;
	}
	
	public function rowCount(){
		return count($this->rows);
	}

	public function combineColumns($keyColumn, $valueColumn) {
		$result = array();
		if (!empty($this -> rows) && 
					array_key_exists($keyColumn, $this -> rows[0]) && 
					array_key_exists($valueColumn, $this -> rows[0])) {
			foreach ($this->rows as $v) {
				$result[$v[$keyColumn]] = $v[$valueColumn];
			}
		}
		return $result;
	}
	
	public function getColumnValues($col){
		$result = array();
		if (!empty($this -> rows) && 
					array_key_exists($col, $this -> rows[0])) {
			foreach ($this->rows as $v) {
				$result[] = $v[$col];
			}
		}
		return $result;		
	}

	public function getFirstValue() {
		$fr = $this->getFirstRow();
		if($fr != null){
			reset($fr);
			return current($fr);
		}
		return null;
	}

	public function getFirstRow() {
		if (!$this->_isEmpty){
			return $this -> rows[0];
		}
		return null;
	}

}
