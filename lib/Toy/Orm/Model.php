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
    protected $dirty = false;
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

    public function isEmptyData($name)
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

    public function isDirty()
    {
        if ($this->newed) {
            return false;
        }
        if ($this->deleted) {
            return false;
        }
        return count($this->changedProperties) > 0;
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
                    if ($relation->getThisProperty()) {
                        $this->data[$name] = $thatModel::find()->eq($relation->getThatProperty(), $this->data[$relation->getThisProperty]);
                    } else {
                        $this->data[$name] = $thatModel::find()->eq($relation->getThatProperty(), $this->getIdValue());
                    }
                    break;
                case Relation::TYPE_CHILD:
                    if ($relation->getThisProperty()) {
                        $this->data[$name] = $thatModel::find()
                            ->eq($relation->getThatProperty(), $this->data[$relation->getThisProperty])
                            ->load()
                            ->getFirst();
                    } else {
                        $this->data[$name] = $thatModel::find()
                            ->eq($relation->getThatProperty(), $this->getIdValue())
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

    public function validateProperties()
    {
        $result = array();
        foreach ($this->changedProperties as $propertyName) {
            $prop = $this->properties[$propertyName];
            if ($this->newed) {
                if ($prop->getInsertable()) {
                    $r = $prop->validate($this->getData($propertyName));
                    if ($r !== true) {
                        $result[] = $propertyName;
                    }
                }

            } else {
                if ($prop->getUpdateable()) {
                    $r = $prop->validate($this->getData($propertyName));
                    if ($r !== true) {
                        $result[] = $propertyName;
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
        if ($this->newed) {
            foreach ($this->properties as $n => $p) {
                if (!$p->getAutoIncrement() && $p->getUnique()) {
                    $c = static::find()
                        ->eq($n, $this->getData($n))
                        ->limit(1)
                        ->count($db);
                    if ($c > 0) {
                        $result[] = $n;
                    }
                }
            }
        }
        return empty($result) ? true : $result;
    }

    public function save($db)
    {
        $cdb = $db ? $db : Helper::openDb();

        $this->beforeSave($db);

        if ($this->deleted) {
            $result = $this->delete($cdb);
        } elseif ($this->newed) {
            $result = $this->insert($cdb);
        } elseif ($this->isChanged()) {
            $result = $this->update($cdb);
        }

        $this->afterSave($cdb);
        $this->saveChildren($cdb);

        return $result;
    }

    protected function saveChildren($db)
    {
        foreach ($this->relations as $name => $relation) {
            switch ($relation->getType()) {
                case Relation::TYPE_CHILDREN:
                    foreach ($this->data[$name] as $child) {
                        $child->save($db);
                    }
                    break;
                case Relation::TYPE_CHILD:
                    $this->data[$name]->save($db);
                    break;
            }
        }
    }

    protected function beforeSave($db)
    {
    }

    protected function afterSave($db)
    {
    }

    protected function insert($db)
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

    protected function update($db)
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

    protected function delete($db = null)
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