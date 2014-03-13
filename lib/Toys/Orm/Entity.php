<?php
namespace Toys\Orm;

class Entity {

	private static $_entitys = array();

	private $_modelClass = '';
	private $_table = '';
	private $_properties = array();
	private $_idProperty = null;

	public function __construct($class, $table) {
		$this -> _table = $table;
		$this -> _modelClass = $class;
	}

	public function getTableName() {
		return $this -> _table;
	}

	public function getModelClass() {
		return $this -> _modelClass;
	}

	public function getIdProperty() {
		return $this -> _idProperty;
	}

	public function getProperty($name) {
		return $this -> _properties[$name];
	}

	public function getProperties() {
		return $this -> _properties;
	}

	public function addProperty(PropertyBase $value) {
		$this -> _properties[$value -> getName()] = $value;
		if ($value -> getPrimaryKey()) {
			$this -> _idProperty = $value;
		}
		return $this;
	}

	public function insert(ModelBase $model, $db) {
		$values = array();
		foreach ($this->_properties as $n => $p) {
			if ($p->getInsertable()){
				$values[$n] = $p -> toDbValue($model -> getData($n));
			}
		}

		$result = $db -> insert($this -> _table, $values);
		if ($this -> _idProperty -> getAutoIncrement()) {
			$model -> setIdValue($db -> getLastInsertId());
		}
		return $result;
	}

	public function update(ModelBase $model, $db) {
		$values = array();
		foreach ($this->_properties as $n => $p) {
			if ($p->getUpdateable()) {
				$values[$n] = $p -> toDbValue($model -> getData($n));
			}
		}
		if(count($values) == 0){
			return false;
		}
		return $db -> update($this -> _table, $values, array( array($this -> _idProperty -> getName() . ' =', $model -> getIdValue())));
	}

	public function delete(ModelBase $model, $db) {
		return $db -> delete($this -> _table, array( array($this -> _idProperty -> getName() . ' =', $model -> getIdValue())));
	}

	public function validate(ModelBase $model) {
		$result = array();
		foreach ($this->_properties as $n => $p) {
			if (!$p -> getAutoIncrement()) {
				$r = $p -> validate($model -> getData($n));
				if ($r !== true) {
					$result[] = $n;
				}
			}
		}
		return empty($result) ? true : $result;
	}

	public function find() {
		$fields = array();
		foreach($this->_properties as $prop){
			$fields[] = $this->_table.'.'.$prop->getName();
		}
		$result = new Finder($this);
		return $result -> select($fields) -> from($this -> _table);
	}

	public static function get($class) {
		if (array_key_exists($class, self::$_entitys)) {
			return self::$_entitys[$class];
		}
		return null;
	}

	public static function register($class, $metadata) {
		$r = new self($class, $metadata['table']);
		foreach ($metadata['properties'] as $p) {
			$r -> addProperty($p);
		}
		self::$_entitys[$class] = $r;
		return $r;
	}

}
