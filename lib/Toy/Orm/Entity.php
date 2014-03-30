<?php
namespace Toy\Orm;

use Toy\Data\Sql\InsertStatement;
use Toy\Data\Sql\UpdateStatement;
use Toy\Data\Sql\DeleteStatement;

class Entity
{

    private static $_entitys = array();

    private $_modelClass = '';
    private $_table = '';
    private $_properties = array();
    private $_idProperty = null;

    public function __construct($class, $table)
    {
        $this->_table = $table;
        $this->_modelClass = $class;
    }

    public function getTableName()
    {
        return $this->_table;
    }

    public function getModelClass()
    {
        return $this->_modelClass;
    }

    public function getIdProperty()
    {
        return $this->_idProperty;
    }

    public function getProperty($name)
    {
        return $this->_properties[$name];
    }

    public function getProperties()
    {
        return $this->_properties;
    }

    public function addProperty(BaseProperty $value)
    {
        $this->_properties[$value->getName()] = $value;
        if ($value->getPrimaryKey()) {
            $this->_idProperty = $value;
        }
        return $this;
    }

    public function insert(Model $model, $db)
    {
        $values = array();
        foreach ($this->_properties as $n => $p) {
            if ($p->getInsertable()) {
                $values[$n] = $p->toDbValue($model->getData($n));
            }
        }

        $result = $db->insert(new InsertStatement($this->_table, $values));
        if ($this->_idProperty->getAutoIncrement()) {
            $model->setIdValue($db->getLastInsertId());
        }
        return $result;
    }

    public function update(Model $model, $db)
    {
        $values = array();
        foreach ($this->_properties as $n => $p) {
            if ($p->getUpdateable()) {
                $values[$n] = $p->toDbValue($model->getData($n));
            }
        }
        if (count($values) == 0) {
            return false;
        }
        $us = new UpdateStatement($this->_table, $values);
        $us->eq($this->_idProperty->getName(), $model->getIdValue());
        return $db->update($us);
    }

    public function delete(Model $model, $db)
    {
        $ds = new DeleteStatement($this->_table);
        $ds->eq($this->_idProperty->getName(), $model->getIdValue());
        return $db->delete($ds);
    }

    public function validateProperties(Model $model)
    {
        $result = array();
        $isUpdate = $model->getIdValue();
        if($isUpdate){
            foreach ($this->_properties as $n => $p) {
                if ($p->getUpdateable()) {
                    $r = $p->validate($model->getData($n));
                    if ($r !== true) {
                        $result[] = $n;
                    }
                }
            }
        }else{
            foreach ($this->_properties as $n => $p) {
                if ($p->getInsertable()) {
                    $r = $p->validate($model->getData($n));
                    if ($r !== true) {
                        $result[] = $n;
                    }
                }
            }
        }

        return empty($result) ? true : $result;
    }

    public function validateUnique($db, Model $model)
    {
        $result = array();
        foreach ($this->_properties as $n => $p) {
            if (!$p->getAutoIncrement() && $p->getUnique()) {
                $c = $this->find()
                        ->selectCount()
                        ->eq($n, $p->toDbValue($model->getData($n)))
                        ->execute($db)
                        ->getFirstValue();
                if ($c > 0) {
                    $result[] = $n;
                }
            }
        }
        return empty($result) ? true : $result;
    }

    public function find()
    {
        $fields = array();
        foreach ($this->_properties as $prop) {
            $fields[] = $this->_table . '.' . $prop->getName();
        }
        $result = new Query($this);
        return $result->select($fields)->from($this->_table);
    }

    public function newModel(){
        return new $this->_modelClass();
    }

    static public function get($class)
    {
        if (array_key_exists($class, self::$_entitys)) {
            return self::$_entitys[$class];
        }
        return null;
    }

    static public function register($class, $metadata)
    {
        $r = new self($class, $metadata['table']);
        foreach ($metadata['properties'] as $p) {
            $r->addProperty($p);
        }
        self::$_entitys[$class] = $r;
        return $r;
    }

}
