<?php
namespace Toy\Orm;

use Toy\Data\Helper;
use Toy\Data\Sql\DeleteStatement;
use Toy\Data\Sql\InsertStatement;
use Toy\Data\Sql\UpdateStatement;

abstract class Model implements \ArrayAccess, \Iterator
{

    private static $_metadatas = array();
    private static $_camelCaseToUnderline = array();

    protected $tableName = '';
    protected $properties = array();
    protected $relations = array();
    protected $idProperty = null;
    protected $changedProperties = array();
    protected $data = array();

    public function __construct($data = array())
    {
        $m = self::$_metadatas[get_class($this)];
        $this->idProperty = $m['idProperty'];
        $this->properties = $m['properties'];
        $this->tableName = $m['table'];
        $this->relations = $m['relations'];
        $this->data = $data;
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
        if ($name == 'load') {
            return $this->_load($arguments[0], $nums > 1 ? $arguments[1] : null);
        } elseif ($name == 'find') {
            return $this->_find();
        } elseif ($name == 'merge') {
            return $this->_merge($arguments[0], $arguments[1], $nums > 2 ? $arguments[2] : null);
        }

        $st = substr($name, 0, 3);
        if ($st == 'get') {
            $pn = self::getUnderlineName(substr($name, 3));
            if ($nums == 1) {
                return $this->getData($pn, $arguments);
            }
            return $this->getData($pn);
        } elseif ($st == 'set') {
            $pn = self::getUnderlineName(substr($name, 3));
            return $this->setData($pn, $arguments[0]);
        }
    }

    static public function __callStatic($name, $arguments)
    {
        $nums = count($arguments);
        if ($name == 'load') {
            $inst = new static();
            return $inst->_load($arguments[0], $nums > 1 ? $arguments[1] : null);
        } elseif ($name == 'find') {
            $inst = new static();
            return $inst->_find();
        } elseif ($name == 'merge') {
            $inst = new static();
            return $inst->_merge($arguments[0], $arguments[1], $nums > 2 ? $arguments[2] : null);
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

        $result = $cdb->insert(new InsertStatement($this->tableName, $values));
        if ($this->idProperty->getAutoIncrement()) {
            $this->setIdValue($cdb->getLastInsertId());
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
        $us = new UpdateStatement($this->tableName, $values);
        $us->eq($this->idProperty->getName(), $this->getIdValue());
        $result = $cdb->update($us);
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
        $ds = new DeleteStatement($this->tableName);
        $ds->eq($this->idProperty->getName(), $this->getIdValue());
        $result = $cdb->delete($ds);
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
            if (array_key_exists($field, $props)) {
                $this->data[$field] = $props[$field]->fromDbValue($value);
            } else {
                $this->data[$field] = $value;
            }
        }
        return $this;
    }

    protected function _load($id, $db = null)
    {
        $fields = array();
        foreach ($this->properties as $prop) {
            $fields[] = $this->tableName . '.' . $prop->getName();
        }
        $q = new Query(get_class($this));
        $q->select($fields)
            ->from($this->tableName)
            ->eq($this->idProperty->getName(), $id)
            ->limit(1);
        $row = $q->execute($db)->getFirstRow();
        if ($row != null) {
            $this->fillRow($row);
            return $this;
        }
        return false;
    }

    protected function _merge($id, $data, $db = null)
    {
        if ($this->_load($id, $db) !== false) {
            $this->fillArray($data);
            return $this;
        }
        return false;
    }

    protected function _find()
    {
        $fields = array();
        foreach ($this->properties as $prop) {
            $fields[] = $this->tableName . '.' . $prop->getName();
        }
        $result = new Collection(get_class($this));
        return $result->select($fields)->from($this->tableName);
    }

    public function deleteBatch(array $ids, $db = null)
    {
        $m = self::$_metadatas[get_called_class()];
        if(is_null($db)){
            return Helper::withTx(function ($db) use ($ids, $m) {
                $ds = new DeleteStatement($this->tableName);
                return $db->delete($ds->in($this->idProperty->getName(), $ids));
            });
        }else{
            $ds = new DeleteStatement($this->tableName);
            return $db->delete($ds->in($this->idProperty->getName(), $ids));
        }
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
            return self::$_metadatas[get_called_class()];
        }
        return self::$_metadatas[$class];
    }

    static public function register($class, $metadata)
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
        self::$_metadatas[$class] = $arr;
    }
}
