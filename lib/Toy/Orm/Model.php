<?php
namespace Toy\Orm;

use Toy\Data\Helper;
use Toy\Data\SelectStatement;
use Toy\Data\Sql\DeleteStatement;
use Toy\Data\Sql\InsertStatement;
use Toy\Data\Sql\UpdateStatement;

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
            if (is_null($this->data[$name])) {
                return $default;
            }
            return $this->data[$name];
        }

        if (array_key_exists($name, $this->relations)) {
            $this->data[$name] = $this->getRelationData($name);
            return $this->data[$name];
        }

        return $default;
    }

    protected function getRelationData($name)
    {
        $relation = $this->relations[$name];

        switch ($relation->getType()) {
            case Relation::TYPE_CHILD:
                $mc = new $relation->getThatModel();
                if (!$this->isEmptyData($this->idProperty->getName())) {
                    $mc->setIdValue($this->data[$this->idProperty->getName()]);
                }
                return $mc;
            case Relation::TYPE_CHILDREN:
                $mc = $relation->getThatModel();
                $res = $mc::find();
                if (!$this->isEmptyData($this->idProperty->getName())) {
                    $res->eq($relation->getThatProperty(), $this->data[$this->idProperty->getName()]);
                }
                return $res;
            case Relation::TYPE_PARENT:
                if ($this->isEmptyData($relation->getThisProperty())) {
                    return null;
                } else {
                    $mc = new $relation->getThatModel();
                    $mc->setIdValue($this->data[$relation->getThisProperty()]);
                    return $mc;
                }
        }
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

    public function getIdValue()
    {
        return $this->getData($this->idProperty->getName());
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
                $c = $this->find()
                    ->selectCount()
                    ->eq($n, $p->toDbValue($this->getData($n)))
                    ->execute($db)
                    ->getFirstValue();
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
        $inst = new static();
        $fields = array();
        foreach ($inst->properties as $prop) {
            $fields[] = $inst->tableName . '.' . $prop->getName();
        }
        $q = new SelectStatement(get_class($inst));
        $q->select($fields)
            ->from($inst->tableName)
            ->eq($inst->idProperty->getName(), $id)
            ->limit(1);
        $row = $q->execute($db)->getFirstRow();
        if ($row != null) {
            $inst->fillRow($row);
            return $inst;
        }
        return false;
    }

    static public function merge($id, $data, $db = null)
    {
        $inst = new static();
        if ($inst->_load($id, $db) !== false) {
            $inst->fillArray($data);
            return $inst;
        }
        return false;
    }

    static public function find()
    {
        $inst = new static();
        $fields = array();
        foreach ($inst->properties as $prop) {
            $fields[] = $inst->tableName . '.' . $prop->getName();
        }
        $result = new Collection(get_class($inst));
        return $result->select($fields)->from($inst->tableName);
    }

    static public function create($data = array())
    {
        return new static($data);
    }

    private static function getUnderlineName($camelCase)
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
        self::$metadatas[__CLASS__] = $arr;
    }
}