<?php
namespace Toy\Orm\Versioning;

use Iterator;
use Toy\Data\Helper;

abstract class Model implements \ArrayAccess, Iterator
{

    private static $_camelCaseToUnderline = array();
    protected $changedProperties = array();
    protected $data = array();
    private $_entity = null;

    public function __construct($data = array())
    {
        $this->_entity = Entity::get(get_class($this));
        $this->data = $data;
    }

    public function __get($name)
    {
        return $this->data[$name];
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

    public function getAllData()
    {
        return $this->data;
    }

    public function getData($name, $default = null)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
        return $default;
    }

    public function setData($name, $value)
    {
        if ($this->_entity->hasProperty($name)) {
            if (!array_key_exists($name, $this->data)) {

                $this->changedProperties[$name] = $name;
            } elseif ($this->data[$name] != $value) {
                $this->changedProperties[$name] = $name;

            }
        }

        $this->data[$name] = $value;
        return $this;
    }

    public function getEntity()
    {
        return $this->_entity;
    }

    public function getMainIdValue()
    {
        return $this->getData($this->_entity->getMainIdProperty()->getName());
    }

    public function setMainIdValue($value)
    {
        return $this->setData($this->_entity->getMainIdProperty()->getName(), $value);
    }

    public function getVersionIdValue()
    {
        return $this->getData($this->_entity->getVersionIdProperty()->getName());
    }

    public function setVersionIdValue($value)
    {
        return $this->setData($this->_entity->getVersionIdProperty()->getName(), $value);
    }

    public function validateProperties()
    {
        return $this->_entity->validateProperties($this);
    }

    public function validateUnique($db = null)
    {
        $cdb = $db ? $db : Helper::openDb();
        return $this->_entity->validateUnique($cdb, $this);
    }

    protected function beforeInsert($db)
    {
    }

    public function insert($db = null)
    {
        $cdb = $db ? $db : Helper::openDb();
        $this->beforeInsert($cdb);
        $result = 0;
        if ($cdb->inTransaction()) {
            $result = $this->_entity->insertMain($this, $cdb);
            $result += $this->_entity->insertVersion($this, $cdb);
        } else {
            try {
                $cdb->begin();
                $result = $this->_entity->insertMain($this, $cdb);
                $result += $this->_entity->insertVersion($this, $cdb);
                $cdb->commit();
            } catch (\Exception $ex) {
                $cdb->rollback();
                throw $ex;
            }
        }
        if ($result) {
            $this->afterInsert($cdb);
        }
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
        $result = 0;
        if ($cdb->inTransaction()) {
            $result = $this->_entity->updateMain($this, $cdb);
            if ($this->getVersionIdValue()) {
                $result += $this->_entity->updateVersion($this, $cdb);
            } else {
                $result += $this->_entity->insertVersion($this, $cdb);
            }
        } else {
            try {
                $cdb->begin();
                $result = $this->_entity->updateMain($this, $cdb);
                if ($this->getVersionIdValue()) {
                    $result += $this->_entity->updateVersion($this, $cdb);
                } else {
                    $result += $this->_entity->insertVersion($this, $cdb);
                }
                $cdb->commit();
            } catch (\Exception $ex) {
                $cdb->rollback();
                throw $ex;
            }
        }
        if ($result) {
            $this->afterUpdate($cdb);
        }
        return $result;
    }

    protected function afterUpdate($db)
    {
    }

    protected function beforeDelete($db)
    {
    }

    public function deleteMain($db = null)
    {
        $cdb = $db ? $db : Helper::openDb();
        $this->beforeDelete($cdb);
        $result = 0;
        if ($cdb->inTransaction()) {
            $result = $this->_entity->deleteMain($this, $cdb);
        } else {
            try {
                $cdb->begin();
                $result = $this->_entity->deleteMain($this, $cdb);
                $cdb->commit();
            } catch (\Exception $ex) {
                $cdb->rollback();
                throw $ex;
            }
        }
        if ($result) {
            $this->afterDelete($cdb);
        }
        return $result;
    }

    public function deleteOne($db = null)
    {
        $cdb = $db ? $db : Helper::openDb();
        $this->beforeDelete($cdb);
        $result = $this->_entity->deleteByVersionId($this, $cdb);
        if ($result) {
            $this->afterDelete($cdb);
        }
        return $result;
    }

    public function deleteVersion($db = null)
    {
        $cdb = $db ? $db : Helper::openDb();
        $this->beforeDelete($cdb);
        $result = $this->_entity->deleteByVersionKey($this, $cdb);
        if ($result) {
            $this->afterDelete($cdb);
        }
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
        $props = $this->_entity->getProperties();
        foreach ($row as $field => $value) {
            if (array_key_exists($field, $props)) {
                $this->data[$field] = $props[$field]->fromDbValue($value);
            } else {
                $this->data[$field] = $value;
            }
        }
        $this->originalData = $this->data;
        return $this;
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

//    static public function merge($id, $data)
//    {
//        return static::load($id)->fillArray($data);
//    }

    static public function loadMain($value)
    {
        $inst = new static();
        $res = $inst->_entity->findMain()
            ->eq($inst->getEntity()->getMainIdProperty()->getName(), $value)
            ->limit(1)
            ->execute()
            ->getFirstModel();

    }

    static public function loadVersion($value)
    {
        $inst = new static();
        return $inst->_entity->findVersion()
            ->eq($inst->getEntity()->getVersionIdProperty()->getName(), $value)
            ->limit(1)
            ->execute()
            ->getFirstModel();
    }

    static public function loadByVersionKey($mid, $key)
    {
        $inst = new static();
        return $inst->_entity->findVersion()
            ->eq($inst->getEntity()->getVersionKeyProperty()->getName(), $key)
            ->eq($inst->getEntity()->getMainIdProperty()->getName(), $mid)
            ->limit(1)
            ->execute()
            ->getFirstModel();
    }

    static public function findMain()
    {
        $inst = new static();
        return $inst->_entity->findMain();
    }

    static public function findVersion()
    {
        $inst = new static();
        return $inst->_entity->findVersion();
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

}
