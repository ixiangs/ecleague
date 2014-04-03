<?php
namespace Toy\Orm;

use Iterator;
use Toy\Collection\ArrayList;
use Toy\Data\Helper;
use Toy\Data\Sql\DeleteStatement;
use Toy\Data\Sql\InsertStatement;
use Toy\Data\Sql\UpdateStatement;
use Toy\Util\ArrayUtil;

abstract class Model implements \ArrayAccess, Iterator
{

    private static $_metadatas = array();
    private static $_camelCaseToUnderline = array();

    protected $tableName = '';
    protected $properties = array();
    protected $relations = array();
    protected $idProperty = null;
//    protected $data = array();
//    protected $relationData = array();
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
        $this->data[$name] = $value;
    }

    public function __call($name, $arguments)
    {
        $st = substr($name, 0, 3);
        if ($st == 'get') {
            $pn = self::getUnderlineName(substr($name, 3));
            if (count($arguments) == 1) {
                return $this->getData($pn, $arguments);
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
            return $this->data[$name];
        }

//        if (array_key_exists($name, $this->relationData)) {
//            return $this->relationData[$name];
//        }

        if (array_key_exists($name, $this->relations)) {
            $this->data[$name] = $this->getChildData($name);
            return $this->relationData[$name];
        }

        return $default;
    }

    protected function getRelationData($name)
    {
        $relation = $this->relations[$name];
        if ($relation->getType() == Relation::TYPE_PARENT) {
            if ($this->isEmptyData($relation->getThisProperty())) {
                return new $relation->getThatModel();
            } else {
                $mc = $relation->getThatModel();
                return $mc::load($this->data[$relation->getThisProperty()]);
            }
        }

        switch ($relation->getType()) {
            case Relation::TYPE_CHILD:
                if ($this->isEmptyData($this->idProperty->getName())) {
                    return new $relation->getThatModel();
                } else {
                    $mc = $relation->getThatModel();
                    $mc::find()
                        ->eq($relation->getThatProperty(), $this->data[$this->idProperty->getName()])
                        ->execute()
                        ->getFirstModel();
                }
            case Relation::TYPE_CHILDREN:
                if ($this->isEmptyData($this->idProperty->getName())) {
                    return new ArrayList();
                } else {
                    $mc = $relation->getThatModel();
                    $mc::find()
                        ->eq($relation->getThatProperty(), $this->data[$this->idProperty->getName()])
                        ->execute()
                        ->getModelList();
                }
        }
    }

    public function setData($name, $value)
    {
        if (array_key_exists($name, $this->relations)) {
            $this->relationData[$name] = $value;
        } else {
            $this->data[$name] = $value;
        }
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

    public function validateChildren()
    {
        $result = array();
        foreach ($this->relationData as $children) {
            if ($children instanceof Model) {
                $r = $children->validateProperties();
                if ($r !== true) {
                    $result = array_merge($result, $r);
                }
            } else {
                foreach ($children as $child) {
                    if ($child !== true) {
                        $result = array_merge($result, $r);
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

    public function saveChildren($db = null)
    {
        $cdb = $db ? $db : Helper::openDb();
        foreach ($this->_entity->getRelations() as $r) {
            if (array_key_exists($r['property'], $this->relationData)) {
                $children = $this->relationData[$r['property']];
                if ($children instanceof Model) {
                    if ($children->getIdValue()) {
                        $children->update($cdb);
                    } else {
                        $children->setData($r['childId'], $this->getIdValue());
                        $children->insert($cdb);
                    }
                } else {
                    foreach ($children as $child) {
                        if ($child->getIdValue()) {
                            $child->update($cdb);
                        } else {
                            $child->setData($r['childId'], $this->getIdValue());
                            $child->insert($cdb);
                        }
                    }
                }
            }
        }
    }

    public function deleteChildren($db = null)
    {
        $cdb = $db ? $db : Helper::openDb();
        foreach ($this->_entity->getRelations() as $r) {
            $re = Entity::get($r['model']);
            $ds = new DeleteStatement($re->getTableName());
            $ds->eq($r['childId'], $this->getIdValue());
            $cdb->delete($ds);
        }
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

    static public function __callStatic($name, $arguments)
    {
        if (substr($name, 0, 4) == 'find') {
            $cn = self::getUnderlineName(substr($name, 4));
            $m = static::getMetadata();
            if (array_key_exists($cn, $m['relations'])) {
                return static::findChildren($cn);
            }
        }
    }

    static public function checkUnique($field, $value)
    {
        $m = static::find()
            ->selectCount()
            ->eq($field, $value)
            ->execute()
            ->getFirstValue();
        return $m > 0;
    }

    static public function merge($id, $data)
    {
        return static::load($id)->fillArray($data);
    }

    static public function load($value)
    {
        $m = self::$_metadatas[get_called_class()];
        return static::find()
            ->eq($m['idProperty']->getName(), $value)
            ->execute()
            ->getFirstModel();
    }

    static public function find()
    {
        $fields = array();
        $cn = get_called_class();
        $m = self::$_metadatas[$cn];
        foreach ($m['properties'] as $prop) {
            $fields[] = $m['table'] . '.' . $prop->getName();
        }
        $result = new Query($cn);
        return $result->select($fields)->from($m['table']);
    }

    static protected function findChildren($name)
    {
        $pm = static::getMetadata();
        $rel = $pm['relations'][$name];
        $cm = self::getMetadata($rel['model']);
        $fields = array();
        foreach ($pm['properties'] as $prop) {
            $fields[] = $pm['table'] . '.' . $prop->getName();
        }
        foreach ($cm['properties'] as $prop) {
            $fields[] = $cm['table'] . '.' . $prop->getName();
        }
        $result = new Query(get_called_class());
        return $result->select($fields)
            ->from($pm['table'])
            ->join($cm['table'],
                $pm['table'] . '.' . $pm['idProperty']->getName(),
                $cm['table'] . '.' . $cm['properties'][$rel['childId']]->getName());
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
                $arr['relations'][$rel['name']] = $rel;
            }
        }
        self::$_metadatas[$class] = $arr;
    }
}
