<?php
namespace Toy\Orm;

use Toy\Data\Helper;

abstract class Model{

    private static $_camelCaseToUnderline = array();

    private $_data = array();
    private $_entity = null;

    public function __construct($data = array())
    {
        $this->_entity = Entity::get(get_class($this));
        $this->_data = $data;
    }

    public function __get($name)
    {
        return $this->_data[$name];
    }

    public function __set($name, $value)
    {
        $this->_data[$name] = $value;
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

    public function getAllData()
    {
        return $this->_data;
    }

    public function getData($name, $default = null)
    {
        if (array_key_exists($name, $this->_data)) {
            return $this->_data[$name];
        }
        return $default;
    }

    public function setData($name, $value)
    {
        $this->_data[$name] = $value;
        return $this;
    }

    public function getEntity()
    {
        return $this->_entity;
    }

    public function getIdValue()
    {
        return $this->getData($this->_entity->getIdProperty()->getName());
    }

    public function setIdValue($value)
    {
        return $this->setData($this->_entity->getIdProperty()->getName(), $value);
    }

    public function validate()
    {
        return $this->_entity->validate($this);
    }

    protected function beforeInsert($db)
    {
    }

    public function insert($db = null)
    {
        $cdb = $db ? $db : Helper::openDb();
        $this->beforeInsert($cdb);
        $result = $this->_entity->insert($this, $cdb);
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
        $result = $this->_entity->update($this, $cdb);
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

    public function delete($db = null)
    {
        $cdb = $db ? $db : Helper::openDb();
        $this->beforeDelete($cdb);
        $result = $this->_entity->delete($this, $cdb);
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
                $this->_data[$field] = $props[$field]->fromDbValue($value);
            } else {
                $this->_data[$field] = $value;
            }
        }
        return $this;
    }

    static public function merge($id, $data)
    {
        return self::load($id)->fillArray($data);
    }

    static public function load($value)
    {
        $f = self::find();
        return $f->eq($f->getEntity()->getIdProperty()->getName(), $value)
                ->execute()
                ->getFirstModel();
    }

    static public function find()
    {
        $inst = new static();
        return $inst->_entity->find();
//        foreach ($conditions as $cond => $value) {
//            $f->andFilter($cond, $value);
//        }
//        return $f;
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
