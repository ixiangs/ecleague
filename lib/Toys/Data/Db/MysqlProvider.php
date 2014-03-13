<?php
namespace Toys\Data\Db;

use Toys\Data\Db\Base;
use Toys\Data\Configuration;
use Toys\Util\ArrayUtil;

class MysqlProvider implements BaseProvider {

	private $_settings = array();
	private $_link = NULL;
	private $_encoding = 'utf8';
	private $_dsn = '';
	private $_user = '';
	private $_password = '';
	private $_options = array();
	private $_isTransaction = false;

	public function __construct($settings) {
		parent::__construct($settings);
		$this -> _dsn = $settings['dsn'];
		$this -> _encoding = ArrayUtil::get($settings, 'encoding', 'utf8');
		$this -> _user = ArrayUtil::get($settings, 'user', 'root');
		$this -> _password = ArrayUtil::get($settings, 'password', '');
		$this -> _options = ArrayUtil::get($settings, 'options', array());
	}

	public function __destruct() {
		$this -> _link = NULL;
		$this -> _isTransaction = false;
	}

	public function getSettings() {
		return $this -> _settings;
	}

	public function isConnected() {
		return $this -> _link ? TRUE : false;
	}

	public function inTransaction() {
		return $this -> _link -> inTransaction();
	}

	public function escape($value) {
		return $this -> _link -> quote($value);
	}

	public function connect() {
		if (!$this -> _link) {
			$this -> _link = new \PDO($this -> _dsn, $this -> _user, $this -> _password, $this -> _options);
			$this -> _link -> query("SET NAMES '" . $this->_encoding. "'");
		}
		return $this;
	}

	public function disconnect() {
		if ($this -> _link) {
			$this -> _link = NULL;
		}
	}

	public function begin() {
		if ($this -> _isTransaction === false) {
			if (!$this -> _link -> beginTransaction()) {
				$this -> handleError($this -> _link -> errorInfo());
			}
			$this -> _isTransaction = TRUE;
		}
		return $this;
	}

	public function commit() {
		if ($this -> _isTransaction) {
			if (!$this -> _link -> commit()) {
				$this -> handleError($statement -> errorInfo());
			}
			$this -> _isTransaction = false;
		}
		return $this;
	}

	public function rollback() {
		if ($this -> _isTransaction) {
			if (!$this -> _link -> rollback()) {
				$this -> handleError($statement -> errorInfo());
			}
			$this -> _isTransaction = false;
		}
		return $this;
	}

	public function getAffectedRows() {
		return $this -> _statement -> rowCount();
	}

	public function getLastInsertId() {
		return $this -> _link -> lastInsertId();
	}

	public function execute($sql, $arguments = array()) {
		if (Configuration::$trace) {
			$this->log($sql, $arguments);
		}		
		$statement = $this -> _link -> prepare(str_replace('{t}', Configuration::$tablePrefix, $sql));
		foreach ($arguments as $name => $value) {
			$statement -> bindValue($name, $value);
		}
		$result = $statement -> execute();
		$statement->closeCursor();
		$this -> handleError($statement -> errorInfo());
		return $result;
	}

	public function fetch($sql, $arguments = array()) {
		if (Configuration::$trace) {
			$this->log($sql, $arguments);
		}		
		$statement = $this -> _link -> prepare(str_replace('{t}', Configuration::$tablePrefix, $sql));
		foreach ($arguments as $name => $value) {
			$statement -> bindValue($name, $value);
		}
		$statement -> execute();
		$this -> handleError($statement -> errorInfo());
		$rows = $statement -> fetchAll(\PDO::FETCH_ASSOC);
		$statement -> closeCursor();
		return new \Toys\Data\Result($rows);
	}

	public function insert($table, $values) {
		$fa = array();
		$va = array();
		$params = array();
		foreach ($values as $n => $v) {
			$fa[] = $n;
			$va[] = ':p' . count($params);
			$params[':p' . count($params)] = $v;
		}
		$sql = sprintf('INSERT INTO %s (%s) VALUES (%s)', $table, implode(',', $fa), implode(',', $va));
		return $this -> execute($sql, $params);
	}

	public function update($table, $values, $conditions = array()) {
		$fv = array();
		$where = '';
		$params = array();
		foreach ($values as $n => $v) {
			$fv[] = $n . '=:p' . count($params);
			$params[':p' . count($params)] = $v;
		}
		if (count($conditions) > 0) {
			$where = $this -> buildWhere($conditions, $params);
		}
		$sql = sprintf('UPDATE %s SET %s %s', $table, implode(',', $fv), $where);
		return $this -> execute(trim($sql), $params);
	}

	public function delete($table, $conditions = array()) {
		$where = '';
		$params = array();
		if (count($conditions) > 0) {
			$where = $this -> buildWhere($conditions, $params);
		}
		$sql = sprintf('DELETE FROM %s %s', $table, $where);
		return $this -> execute(trim($sql), $params);
	}

	public function query($query) {
		$params = array();
		$select = $query -> getSelect();
		if (count($select) > 0) {
			$sql = 'SELECT ' . implode(',', $select);
		} else {
			$sql = 'SELECT *';
		}
		$sql .= ' FROM ' . $query -> getFrom();

		$joins = $query -> getJoins();
		foreach ($joins as $v) {
			$sql .= ' ' . strtoupper($v[0]) . ' JOIN '. $v[1] .' ON ' . $v[2] . '=' . $v[3];
		}

		$conditions = $query -> getWhere();
		if (count($conditions) > 0) {
			$sql .= ' ' . $this -> buildWhere($conditions, $params);
		}
		
		$orders = $query->getOrderBy();
		if(count($orders) > 0){
			$arr = array();
			foreach($orders as $f=>$d){
				$arr[] = $f.' '.$d;
			}
			$sql .= ' ORDER BY '.implode(',', $arr);
		}
		
		$offset = $query->getOffset();
		$limit = $query->getLimit();
		if($offset + $limit > 0){
			$sql .= ' LIMIT '.$offset.','.$limit;
		}

		return $this -> fetch($sql, $params);
	}

	private function buildWhere($conditions, array &$params) {
		$result = array();
		foreach ($conditions as $v) {
			if (is_array($v)) {
				if (count($result) > 0 and end($result) != 'AND' and end($result) != 'OR') {
					$result[] = 'AND';
				}
				$arr = explode(' ', $v[0]);
				switch(trim($arr[1])) {
					case '>' :
					case '<' :
					case '=' :
					case '!=' :
					case '>=' :
					case '<=' :
						$result[] = $arr[0] . $arr[1] . ':p' . count($params);
						$params[':p' . count($params)] = $v[1];
						break;
					case 'isnull' :
						$result[] = $arr[0] . ' IS NULL';
						break;
					case 'notnull' :
						$result[] = $arr[0] . ' IS NOT NULL';
						break;
					case 'in' :
					case 'notin' :
						$items = array();
						foreach ($v[1] as $i) {
							$items[] = $this -> escape($i);
						}
						$result[] = $arr[0] . ($arr[1] == 'in' ? ' IN(' : ' NOT IN(') . implode(',', $items) . ')';
						break;
					case 'like' :
						$result[] = $arr[0] . $arr[1] . "'" . $this -> escape($v) . "'";
				}
			} else {
				$result[] = strtoupper($v);
			}
		}
		return 'WHERE ' . implode(' ', $result);
	}

	private function handleError($err) {
		if ($err[1]) {
			throw new \Toys\Data\Exception($err[2]);
		}
	}

	private function log($sql, $arguments){
		$content = $sql;
		foreach($arguments as $n=>$v){
			$content .= '['.$n.':'.$v.']';
		}
		Joy::logger()->v($content, 'sql');
	}
}
