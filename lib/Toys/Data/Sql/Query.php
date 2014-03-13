<?php
namespace Toys\Data;

class Query {

	private $_select = array();
	private $_from = null;
	private $_where = array();
	private $_joins = array();
	private $_orderby = array();
	private $_offset = 0;
	private $_limit = 0;

	public function select() {
		$args = func_get_args();
		if (func_num_args() == 1 && is_array($args[0])) {
			$this -> _select = array_merge($this -> _select, $args[0]);
		} else {
			foreach ($args as $v) {
				$this -> _select[] = $v;
			}
		}
		return $this;
	}
	
	public function count(){
		$this->_select = array('COUNT(*)');
		return $this;
	}

	public function from($from) {
		$this -> _from = $from;
		return $this;
	}

	public function joinInner($table, $left, $right) {
		$this -> _joins[] = array('inner', $table, $left, $right);
		return $this;
	}

	public function joinLeft($table, $left, $right) {
		$this -> _joins[] = array('left', $table, $left, $right);
		return $this;
	}

	public function joinRight($table, $left, $right) {
		$this -> _joins[] = array('right', $table, $left, $right);
		return $this;
	}

	public function andFilter($condition, $value) {
		if (count($this -> _where) > 0) {
			$this -> _where[] = 'and';
		}
		$this -> _where[] = array($condition, $value);
		return $this;
	}

	public function orFilter($condition, $value = NULL) {
		if (count($this -> _where) > 0) {
			$this -> _where[] = 'or';
		}
		$this -> _where[] = array($condition, $value = NULL);
		return $this;
	}

	public function asc() {
		$args = func_get_args();
		foreach ($args as $arg) {
			$this -> _orderby[$arg] = 'ASC';
		}
		return $this;
	}

	public function desc() {
		$args = func_get_args();
		foreach ($args as $arg) {
			$this -> _orderby[$arg] = 'DESC';
		}
		return $this;
	}

	public function limit($count, $offset = 0) {
		$this -> _offset = $offset;
		$this -> _limit = $count;
		return $this;
	}

	public function resetSelect() {
		$this -> _select = array();
		return $this;
	}

	public function resetJoin() {
		$this -> _joins = array();
		return $thsi;
	}

	public function resetWhere() {
		$this -> _where = array();
		return $this;
	}

	public function getSelect() {
		return $this -> _select;
	}

	public function getFrom() {
		return $this -> _from;
	}

	public function getJoins() {
		return $this -> _joins;
	}

	public function getWhere() {
		return $this -> _where;
	}

	public function getOrderBy() {
		return $this -> _orderby;
	}

	public function getOffset() {
		return $this -> _offset;
	}

	public function getLimit() {
		return $this -> _limit;
	}

}
