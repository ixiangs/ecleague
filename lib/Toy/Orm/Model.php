<?php
namespace Toy\Orm;

use Toy\Db\Helper;
use Toy\Db\SelectStatement;

abstract class Model implements \ArrayAccess, \Iterator
{

    protected static $metadatas = array();
    private static $_camelCaseToUnderline = array();

    protected $tableName = '';
    protected $properties = array();
    protected $relations = array();
    protected $idProperty = null;
    protected $changedProperties = array();
    protected $originalData = array();
    protected $data = array();

    public function __construct($data = array())
    {
        $m = self::$metadatas[get_class($this)];
        $this->tableName = $m['table'];
        $this->idProperty = $m['idProperty'];
        $this->properties = $m['properties'];
        $this->relations = $m['relations'];
        foreach ($this->properties as $prop) {
            $this->data[$prop->getName()] = $prop->getDefaultValue();
        }
        $this->data = array_merge($this->data, $data);
    }

    public function __get($name)
    {
        return $this->getData($name);
    }

    public function __set($name, $value)
    {
        $this->setData($name, $value);
    }

    public function __call($name, $arguments)
    {
        $nums = count($arguments);
        $st = substr($name, 0, 3);
        if ($st == 'get') {
            $pn = self::getUnderlineName(substr($name, 3));
            if ($nums == 1) {
                return $this->getData($pn, $arguments[0]);
            }
            return $this->getData($pn);
        } elseif ($st == 'set') {
            $pn = self::getUnderlineName(substr($name, 3));
            return $this->setData($pn, $arguments[0]);
        }
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->data);
    }

    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->data[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    public function current()
    {
        return current($this->data);
    }

    public function key()
    {
        return key($this->data);
    }

    public function next()
    {
        return next($this->data);
    }

    public function rewind()
    {
        return reset($this->data);
    }

    public function valid()
    {
        return key($this->data) !== null;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getIdProperty()
    {
        return $this->idProperty;
    }

    public function getProperty($name)
    {
        return $this->properties[$name];
    }

    public function getProperties()
    {
        return $this->properties;
    }

    public function addProperty(BaseProperty $value)
    {
        $this->properties[$value->getName()] = $value;
        if ($value->getPrimaryKey()) {
            $this->idProperty = $value;
        }
        return $this;
    }

    public function hasProperty($name)
    {
        return array_key_exists($name, $this->properties);
    }

    public function getRelations()
    {
        return $this->relations;
    }

    public function hasRelation($name)
    {
        return array_key_exists($name, $this->relations);
    }

    public function isEmptyData($name)
    {
        if (!array_key_exists($name, $this->data)) {
            return true;
        }
        return empty($this->data[$name]);
    }

    public function getAllData()
    {
        return $this->data;
    }

    public function getData($name, $default = null)
    {
        if (array_key_exists($name, $this->data)) {
            if(is_null($this->data[$name])){
                return $default;
            }
            return $this->data[$name];
        }

        if (array_key_exists($name, $this->relations)) {
            $relation = $this->relations[$name];
            $mc = $relation->getThatModel();
            if($relation->getThisProperty()){
                $this->data[$name] = $mc::find()->eq($relation->getThatProperty(), $this->data[$relation->getThisProperty]);
            }else{
                $this->data[$name] = $mc::find()->eq($relation->getThatProperty(), $this->getIdValue());
            }
            return $this->data[$name];
        }

        return $default;
    }

    public function setData($name, $value)
    {
        if (array_key_exists($name, $this->properties) && !array_key_exists($name, $this->changedProperties)) {
            if (array_key_exists($name, $this->data)) {
                if ($this->data[$name] != $value) {
                    $this->changedProperties[] = $name;
                }
            } else {
                $this->changedProperties[] = $name;
            }
        }

        $this->data[$name] = $value;
        return $this;
    }

    public function getIdValue($default = null)
    {
        return $this->getData($this->idProperty->getName(), $default);
    }

    public function setIdValue($value)
    {
        return $this->setData($this->idProperty->getName(), $value);
    }

    public function validateProperties()
    {
        $result = array();
        if ($this->getIdValue()) {
            foreach ($this->properties as $n => $p) {
                if ($p->getUpdateable()) {
                    $r = $p->validate($this->getData($n));
                    if ($r !== true) {
                        $result[] = $n;
                    }
                }
            }
        } else {
            foreach ($this->properties as $n => $p) {
                if ($p->getInsertable()) {
                    $r = $p->validate($this->getData($n));
                    if ($r !== true) {
                        $result[] = $n;
                    }
                }
            }
        }
        return empty($result) ? true : $result;
    }

    public function validateUnique($db = null)
    {
        $cdb = $db ? $db : Helper::openDb();
        $result = array();
        foreach ($this->properties as $n => $p) {
            if (!$p->getAutoIncrement() && $p->getUnique()) {
                $c = static::find()
                    ->eq($n, $p->toDbValue($this->getData($n)))
                    ->count($db);
                if ($c > 0) {
                    $result[] = $n;
                }
            }
        }
        return empty($result) ? true : $result;
    }

    protected function beforeInsert($db)
    {
    }

    public function insert($db = null)
    {
        $cdb = $db ? $db : Helper::openDb();
        $this->beforeInsert($cdb);
        $values = array();
        foreach ($this->properties as $n => $p) {
            if ($p->getInsertable()) {
                $values[$n] = $p->toDbValue($this->getData($n));
            }
        }


        if ($this->idProperty->getAutoIncrement()) {
            $id = Helper::insert($this->tableName, $values)->executeLastInsertId($cdb);
            if ($id > 0) {
                $this->setIdValue($id);
                $result = true;
            } else {
                $result = false;
            }
        } else {
            $result = Helper::insert($this->tableName, $values)->execute($cdb);
        }
        $this->afterInsert($cdb);
        return $result;
    }

    protected function afterInsert($db)
    {
    }

    protected function beforeUpdate($db)
    {
    }

    public function update($db = null)
    {
        $cdb = $db ? $db : Helper::openDb();
        $this->beforeUpdate($cdb);
        $values = array();
        foreach ($this->properties as $n => $p) {
            if ($p->getUpdateable()) {
                $values[$n] = $p->toDbValue($this->getData($n));
            }
        }
        if (count($values) == 0) {
            return false;
        }
        $result = Helper::update($this->tableName, $values)
            ->eq($this->idProperty->getName(), $this->getIdValue())
            ->execute($cdb);
        $this->afterUpdate($cdb);
        return $result;
    }

    protected function afterUpdate($db)
    {
    }

    protected function beforeDelete($db)
    {
    }

    public function delete($db = null)
    {
        $cdb = $db ? $db : Helper::openDb();
        $this->beforeDelete($cdb);
        $result = Helper::delete($this->tableName)
            ->eq($this->idProperty->getName(), $this->getIdValue())
            ->execute($cdb);
        $this->afterDelete($cdb);
        return $result;
    }

    protected function afterDelete($db)
    {
    }

    public function fillArray(array $values)
    {
        foreach ($values as $k => $v) {
            $this->setData($k, $v);
        }
        return $this;
    }

    public function fillRow(array $row)
    {
        $props = $this->getProperties();
        foreach ($row as $field => $value) {
            $this->data[$field] = array_key_exists($field, $props) ? $props[$field]->fromDbValue($value) : $value;
        }
        $this->originalData = $this->data;
        return $this;
    }

    static public function load($id, $db = null)
    {
        $calledClass = get_called_class();
        $metadata = self::$metadatas[$calledClass];
        $table = $metadata['table'];
        $fields = array();
        foreach ($metadata['properties'] as $prop) {
            $fields[] = $table . '.' . $prop->getName();
        }
        $row = Helper::select($table, $fields)
            ->eq($metadata['idProperty']->getName(), $id)
            ->limit(1)
            ->execute($db)
            ->getFirstRow();
        if ($row != null) {
            $inst = new $calledClass();
            $inst->fillRow($row);
            return $inst;
        }
        return false;
    }

    static public function merge($id, $data, $db = null)
    {
        $inst = static::load($id, $db);
        if ($inst !== false) {
            $inst->fillArray($data);
            return $inst;
        }
        return false;
    }

    static public function find()
    {
        $calledClass = get_called_class();
        $metadata = self::$metadatas[$calledClass];
        $table = $metadata['table'];
        $fields = array();
        foreach ($metadata['properties'] as $prop) {
            $fields[] = $table . '.' . $prop->getName();
        }
        $result = new Collection($calledClass);
        return $result->select($fields)->from($table);
    }

    static public function create($data = array())
    {
        return new static($data);
    }

    static private function getUnderlineName($camelCase)
    {
        if (!array_key_exists($camelCase, self::$_camelCaseToUnderline)) {
            preg_match_all('/([A-Z]{1}[a-z0-9]+)/', $camelCase, $matches);
            self::$_camelCaseToUnderline[$camelCase] = implode('_', array_map('lcfirst', $matches[0]));
        }
        return self::$_camelCaseToUnderline[$camelCase];
    }

    static public function getMetadata($class = null)
    {
        if (is_null($class)) {
            return self::$metadatas[get_called_class()];
        }
        return self::$metadatas[$class];
    }

    static public function register($metadata)
    {
        $arr = array(
            'table' => $metadata['table'],
            'properties' => array(),
            'relations' => array()
        );
        foreach ($metadata['properties'] as $prop) {
            $arr['properties'][$prop->getName()] = $prop;
            if ($prop->getPrimaryKey()) {
                $arr['idProperty'] = $prop;
            }
        }
        if (array_key_exists('relations', $metadata)) {
            foreach ($metadata['relations'] as $rel) {
                $arr['relations'][$rel->getPropertyName()] = $rel;
            }
        }
        self::$metadatas[get_called_class()] = $arr;
    }
}