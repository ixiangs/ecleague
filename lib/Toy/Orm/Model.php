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
    protected $newed = false;
    protected $deleted = false;

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
        $this->changedProperties[] = array_keys($this->properties);
        $this->newed = true;
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

    public function isEmptyProperty($name)
    {
        if (!array_key_exists($name, $this->data)) {
            return true;
        }
        return empty($this->data[$name]);
    }

    public function isChanged()
    {
        if ($this->newed) {
            return true;
        }

        return count($this->changedProperties) > 0;
    }

    public function isNewed()
    {
        return $this->newed;
    }

    public function isDeleted()
    {
        return $this->deleted;
    }

    public function markNewed()
    {
        $this->newed = true;
        return $this;
    }

    public function markDeleted()
    {
        $this->deleted = true;
        return $this;
    }

    public function markClean()
    {
        $this->newed = false;
        $this->deleted = false;
        $this->changedProperties = array();
        return $this;
    }

    public function getAllData()
    {
        return $this->data;
    }

    public function setAllData(array $values)
    {
        foreach ($values as $k => $v) {
            $this->setData($k, $v);
        }
        return $this;
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
            $relation = $this->relations[$name];
            $type = $relation->getType();
            $thatModel = $relation->getThatModel();
            switch ($type) {
                case Relation::TYPE_CHILDREN:
                    $eqValue = $relation->getThisProperty() ?
                        $this->data[$relation->getThisProperty()] :
                        $this->getIdValue();
                    $find = $thatModel::find();
                    if (!empty($eqValue)) {
                        $find->eq($relation->getThatProperty(), $eqValue);
                    }
                    $this->data[$name] = $find;
                    break;
                case Relation::TYPE_CHILD:
                    $eqValue = $relation->getThisProperty() ?
                        $this->data[$relation->getThisProperty()] :
                        $this->getIdValue();
                    if (!empty($eqValue)) {
                        $this->data[$name] = new $thatModel();
                    } else {
                        $this->data[$name] = $thatModel::find()->eq($relation->getThatProperty(), $eqValue)
                            ->load()
                            ->getFirst();
                    }
                    break;
                case Relation::TYPE_PARENT:
                    $this->data[$name] = $thatModel::load($this->data[$relation->getThisProperty]);
                    break;
            }

            return $this->data[$name];
        }

        return $default;
    }

    public function setData($name, $value)
    {
        if (array_key_exists($name, $this->properties) && !array_key_exists($name, $this->changedProperties)) {
            if (array_key_exists($name, $this->data)) {
                if ($this->properties[$name]->getAlwaysDirty()) {
                    $this->changedProperties[] = $name;
                } elseif ($this->data[$name] != $value) {
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

    public function validate()
    {
        $result = $this->validateProperties();
        if (!$result) {
            return $result;
        }

        return $this->validateChildren();
    }

    public function validateProperties()
    {
        $result = array();
        if ($this->newed) {
            foreach ($this->properties as $name => $property) {
                if ($property->getInsertable()) {
                    $r = $property->validate($this->getData($name));
                    if ($r !== true) {
                        $result[] = $name;
                    }
                }
            }
        } elseif ($this->isChanged()) {
            foreach ($this->changedProperties as $name) {
                $property = $this->properties[$name];
                if ($property->getUpdateable()) {
                    $r = $property->validate($this->getData($name));
                    if ($r !== true) {
                        $result[] = $name;
                    }
                }
            }
        }

        return empty($result) ? true : $result;
    }

    public function validateChildren()
    {
        foreach ($this->relations as $name => $relation) {
            switch ($relation->getType()) {
                case Relation::TYPE_CHILDREN:
                    foreach ($this->data[$name] as $child) {
                        if (!$child->validateProperties()) {
                            return false;
                        }
                        if (!$child->validateChildren()) {
                            return false;
                        }
                    }
                    return true;
                    break;
                case Relation::TYPE_CHILD:
                    if (!$this->data[$name]->validateProperties()) {
                        return false;
                    }
                    if (!$this->data[$name]->validateChildren()) {
                        return false;
                    }
                    return true;
                    break;
            }
        }
        return true;
    }

    public function checkUnique($db = null)
    {
        $cdb = $db ? $db : Helper::openDb();
        $result = array();
        if ($this->newed) {
            foreach ($this->properties as $n => $p) {
                if (!$p->getAutoIncrement() && $p->getUnique()) {
                    $c = static::find()
                        ->eq($n, $this->getData($n))
                        ->limit(1)
                        ->count($cdb);
                    if ($c > 0) {
                        $result[] = $n;
                    }
                }
            }
        }
        return empty($result) ? true : $result;
    }

    public function save($db = null)
    {
        $cdb = $db ? $db : Helper::openDb();

        $result = true;
        if ($this->deleted) {
            $this->beforeDelete($cdb);
            $this->deleteChildren($cdb);
            $result = $this->deleteSelf($cdb);
            $this->afterDelete($cdb);
        } else {
            if ($this->newed) {
                $this->beforeInsert($cdb);
                $result = $this->insertSelf($cdb);
                $this->afterInsert($cdb);
            } elseif ($this->isChanged()) {
                $this->beforeUpdate($cdb);
                $result = $this->updateSelf($cdb);
                $this->afterUpdate($cdb);
            }
            if ($result) {
                $result = $this->saveChildren($cdb);
            }
        }

        return $result;
    }

    public function delete($db)
    {
        return $this->markDeleted()->save($db);
    }

    protected function saveChildren($db)
    {
        foreach ($this->relations as $name => $relation) {
            switch ($relation->getType()) {
                case Relation::TYPE_CHILDREN:
                    if (array_key_exists($name, $this->data)) {
                        foreach ($this->data[$name] as $child) {
                            if (!$child->save($db)) {
                                return false;
                            }
                        }
                    }
                    break;
                case Relation::TYPE_CHILD:
                    if (array_key_exists($name, $this->data)) {
                        if ($this->data[$name]->save($db)) {
                            return false;
                        }
                    }
                    break;
            }
        }
        return true;
    }

    protected function deleteChildren($db)
    {
        foreach ($this->relations as $name => $relation) {
            switch ($relation->getType()) {
                case Relation::TYPE_CHILDREN:
                    foreach ($this->data[$name] as $child) {
                        $child->delete($db);
                    }
                    break;
                case Relation::TYPE_CHILD:
                    $this->data[$name]->delete($db);
                    break;
            }
        }
    }


    protected function beforeDelete($db)
    {
    }

    protected function afterDelete($db)
    {
    }


    protected function beforeInsert($db)
    {
    }

    protected function afterInsert($db)
    {
    }

    protected function beforeUpdate($db)
    {
    }

    protected function afterUpdate($db)
    {
    }

    protected function insertSelf($db)
    {
        $values = array();
        foreach ($this->properties as $n => $p) {
            if ($p->getInsertable()) {
                $values[$n] = $p->toDbValue($this->getData($n));
            }
        }


        if ($this->idProperty->getAutoIncrement()) {
            $id = Helper::insert($this->tableName, $values)->executeLastInsertId($db);
            if ($id > 0) {
                $this->setIdValue($id);
                $result = true;
            } else {
                $result = false;
            }
        } else {
            $result = Helper::insert($this->tableName, $values)->execute($db);
        }
        return $result;
    }

    protected function updateSelf($db)
    {
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
            ->execute($db);
        return $result;
    }

    protected function deleteSelf($db = null)
    {
        $result = Helper::delete($this->tableName)
            ->eq($this->idProperty->getName(), $this->getIdValue())
            ->execute($db);
        return $result;
    }

    public function toDbValues()
    {
        $result = array();
        foreach ($this->properties as $n => $p) {
            $result[$n] = $p->toDbValue($this->data[$n]);
        }
        return $result;
    }

    public function fromDbValues(array $row)
    {
        $props = $this->getProperties();
        foreach ($row as $field => $value) {
            $this->data[$field] = array_key_exists($field, $props) ? $props[$field]->fromDbValue($value) : $value;
        }
        $this->originalData = $this->data;
        return $this;
    }

    static public function propertiesToFields($includes = null, $ignores = null, $withTable = true)
    {
        $metadata = self::$metadatas[get_called_class()];
        $table = $withTable ? $metadata['table'] . '.' : '';
        $_includes = $includes ? is_array($includes) ? $includes : array($includes) : null;
        $_ignores = $ignores ? is_array($ignores) ? $ignores : array($ignores) : null;
        $result = array();
        foreach ($metadata['properties'] as $name => $prop) {
            if ($_includes && !in_array($name, $_includes)) {
                continue;
            }
            if ($_ignores && in_array($name, $_ignores)) {
                continue;
            }
            $result[] = $table . $name;
        }

        return $result;
    }

    static public function propertyToField($name, $withTable = true)
    {
        $metadata = self::$metadatas[get_called_class()];
        $table = $withTable ? $metadata['table'] . '.' : '';
        if (array_key_exists($name, $metadata['properties'])) {
            return $table . $name;
        }


        return $name;
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
            $inst->fromDbValues($row)->markClean();
            return $inst;
        }
        return false;
    }

    static public function merge($id, $data, $db = null)
    {
        $inst = static::load($id, $db);
        if ($inst !== false) {
            $inst->setAllData($data);
            return $inst;
        }
        return false;
    }

    static public function find($allFields = true)
    {
        $calledClass = get_called_class();
        $metadata = self::$metadatas[$calledClass];
        $table = $metadata['table'];
        $result = new Collection($calledClass);
        if ($allFields) {
            $fields = array();
            foreach ($metadata['properties'] as $prop) {
                $fields[] = $table . '.' . $prop->getName();
            }
            $result->select($fields);
        }

        return $result->from($table);
    }

    static public function create($data = array())
    {
        return new static($data);
    }

    static public function batchDelete(array $ids, $db = null)
    {
        $calledClass = get_called_class();
        $metadata = self::$metadatas[$calledClass];
        return Helper::delete($metadata['table'])
            ->in($metadata['idProperty']->getName(), $ids)
            ->execute($db);
    }

    static public function batchUpdate(array $data, array $ids, $db = null)
    {
        $calledClass = get_called_class();
        $metadata = self::$metadatas[$calledClass];
        return Helper::update($metadata['table'], $data)
            ->in($metadata['idProperty']->getName(), $ids)
            ->execute($db);
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