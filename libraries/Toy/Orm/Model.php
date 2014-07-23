<?php
namespace Toy\Orm;

use Toy\Orm\Db\Helper;

abstract class Model implements \ArrayAccess, \Iterator
{

    private static $_camelCaseToUnderline = array();

    protected $metadata = null;
    protected $changedProperties = array();
    protected $originalData = array();
    protected $data = array();
    protected $newed = false;
    protected $deleted = false;

    public function __construct($data = array())
    {
        $this->metadata = Metadata::get(get_class($this));
        foreach ($this->metadata->getProperties() as $property) {
            $this->data[$property->getName()] = $property->getDefaultValue();
        }
        $this->data = array_merge($this->data, $data);
        $this->changedProperties[] = array_keys($this->metadata->getProperties());
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
        return null;
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

    public function isChanged()
    {
        if ($this->newed) {
            return true;
        }

        return count($this->changedProperties) > 0;
    }

    public function propertyIsChanged($name)
    {
        return in_array($name, $this->changedProperties);
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

        return $default;
    }

    public function setData($name, $value)
    {
        if ($this->metadata->hasProperty($name) && !array_key_exists($name, $this->changedProperties)) {
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
        return $this->getData($this->metadata->getPrimaryKey()->getName(), $default);
    }

    public function setIdValue($value)
    {
        return $this->setData($this->metadata->getPrimaryKey()->getName(), $value);
    }

    public function validate()
    {
        if ($this->newed) {
            return $this->validateInsert();
        } elseif ($this->isChanged()) {
            return $this->validateUpdate();
        }


        return true;
    }

    protected function validateInsert()
    {
        $result = array();
        foreach ($this->metadata->getProperties() as $name => $property) {
            if ($property->getInsertable()) {
                $r = $property->validate($this->getData($name));
                if ($r !== true) {
                    $result[] = $name;
                }
            }
        }

        return empty($result) ? true : $result;
    }

    protected function validateUpdate()
    {
        $result = array();

        foreach ($this->changedProperties as $name) {
            $property = $this->metadata->getProperty($name);
            if ($property->getUpdateable()) {
                $r = $property->validate($this->getData($name));
                if ($r !== true) {
                    $result[] = $name;
                }
            }
        }

        return empty($result) ? true : $result;
    }

    public function checkUnique($db = null)
    {
        $cdb = $db ? $db : Helper::openDb();
        $result = array();
        if ($this->newed) {
            foreach ($this->metadata->getProperties() as $n => $p) {
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
        $result = true;
        if ($this->deleted) {
            $result = $this->delete($db);
        } else {
            if ($this->newed) {
                $result = $this->insert($db);
            } elseif ($this->isChanged()) {
                $result = $this->update($db);
            }
        }

        return $result;
    }

    public function delete($db = null)
    {
        $cdb = $db ? $db : Helper::openDb();
        $this->beforeDelete($cdb);
        $result = Helper::delete($this->metadata->getTableName())
            ->eq($this->metadata->getPrimaryKey()->getName(), $this->getIdValue())
            ->execute($db);
        $this->afterDelete($cdb);
        return $result;
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

    protected function insert($db)
    {
        $cdb = $db ? $db : Helper::openDb();
        $this->beforeInsert($cdb);
        $values = array();
        foreach ($this->metadata->getProperties() as $n => $p) {
            if ($p->getInsertable()) {
                $values[$n] = $p->toDbValue($this->getData($n));
            }
        }

        if ($this->metadata->getPrimaryKey()->getAutoIncrement()) {
            $id = Helper::insert($this->metadata->getTableName(), $values)->executeLastInsertId($db);
            if ($id > 0) {
                $this->setIdValue($id);
                $result = true;
            } else {
                $result = false;
            }
        } else {
            $result = Helper::insert($this->metadata->getTableName(), $values)->execute($db);
        }
        $this->afterInsert($cdb);
        return $result;
    }

    protected function update($db)
    {
        $cdb = $db ? $db : Helper::openDb();
        $this->beforeUpdate($cdb);
        $values = array();
        foreach ($this->metadata->getProperties() as $n => $p) {
            if ($p->getUpdateable()) {
                $values[$n] = $p->toDbValue($this->getData($n));
            }
        }
        if (count($values) == 0) {
            return false;
        }
        $result = Helper::update($this->metadata->getTableName(), $values)
            ->eq($this->metadata->getPrimaryKey()->getName(), $this->getIdValue())
            ->execute($db);
        $this->afterUpdate($cdb);
        return $result;
    }

    public function fromDbRow(array $row)
    {
        $props = $this->metadata->getProperties();
        foreach ($row as $field => $value) {
            $this->data[$field] = array_key_exists($field, $props) ?
                $props[$field]->fromDbValue($value) :
                $value;
        }
        $this->originalData = $this->data;
        return $this;
    }

    static public function propertiesToFields($includes = null, $ignores = null, $withTable = true)
    {
        $metadata = Metadata::get(get_called_class());
        $table = $withTable ? $metadata->getTableName() . '.' : '';
        $_includes = $includes ? is_array($includes) ? $includes : array($includes) : null;
        $_ignores = $ignores ? is_array($ignores) ? $ignores : array($ignores) : null;
        $result = array();
        foreach ($metadata->getProperties() as $name => $prop) {
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

    static public function propertyToField($name, $as = null, $withTable = true)
    {
        $metadata = Metadata::get(get_called_class());
        $table = $withTable ? $metadata->getTableName() . '.' : '';
        $as = $as ? ' AS ' . $as : '';
        if ($metadata->hasProperty($name)) {
            return $table . $name . $as;
        }


        return $name;
    }

    static public function load($id, $db = null)
    {
        $calledClass = get_called_class();
        $metadata = Metadata::get($calledClass);
        $table = $metadata->getTableName();
        $fields = array();
        foreach ($metadata->getProperties() as $prop) {
            $fields[] = $table . '.' . $prop->getName();
        }
        $row = Helper::select($table, $fields)
            ->eq($metadata->getPrimaryKey()->getName(), $id)
            ->limit(1)
            ->fetchFirstRow($db);
        if ($row != null) {
            $inst = new $calledClass();
            $inst->fromDbRow($row)->markClean();
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
        $metadata = Metadata::get($calledClass);
        $table = $metadata->getTableName();
        $result = new Query($calledClass);
        if ($allFields) {
            $fields = array();
            foreach ($metadata->getProperties() as $prop) {
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
        $metadata = Metadata::get($calledClass);
        return Helper::delete($metadata->getTableName())
            ->in($metadata->getPrimaryKey()->getName(), $ids)
            ->execute($db);
    }

    static public function batchUpdate(array $data, array $ids, $db = null)
    {
        $calledClass = get_called_class();
        $metadata = Metadata::get($calledClass);
        return Helper::update($metadata->getTableName(), $data)
            ->in($metadata->getPrimaryKey()->getName(), $ids)
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
            return Metadata::get(get_called_class());
        }
        return Metadata::get($class);
    }

    static public function registerMetadata($metadata)
    {
        Metadata::register(
            get_called_class(),
            $metadata['table'],
            $metadata['properties'],
            array_key_exists('relations', $metadata) ? $metadata['relations'] : array());
    }
}