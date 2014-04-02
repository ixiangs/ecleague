<?php
namespace Toy\Orm\Versioning;

use Toy\Data\Sql\InsertStatement;
use Toy\Data\Sql\UpdateStatement;
use Toy\Data\Sql\DeleteStatement;
use Toy\Orm\BaseProperty;
use Toy\Orm\IntegerProperty;
use Toy\Orm\Query;

class Entity
{

    private static $_entitys = array();

    private $_modelClass = '';
    private $_mainTable = '';
    private $_versionTable = '';
    private $_properties = array();
    private $_mainIdProperty = null;
    private $_mainProperties = array();
    private $_versionProperties = array();
    private $_versionIdProperty = null;
    private $_versionKeyProperty = null;
    private $_versionForeignProperty = null;

    public function __construct($class, $table)
    {
        $this->_mainTable = $table . '_main';
        $this->_versionTable = $table . '_version';
        $this->_modelClass = $class;
    }

    public function getMainTableName()
    {
        return $this->_mainTable;
    }

    public function getVersionTableName()
    {
        return $this->_versionTable;
    }

    public function getModelClass()
    {
        return $this->_modelClass;
    }

    public function getMainIdProperty()
    {
        return $this->_mainIdProperty;
    }

    public function getVersionIdProperty()
    {
        return $this->_versionIdProperty;
    }

    public function getVersionKeyProperty()
    {
        return $this->_versionKeyProperty;
    }

    public function getVersionForeignProperty()
    {
        return $this->_versionForeignProperty;
    }

    public function hasProperty($name)
    {
        return array_key_exists($name, $this->_properties);
    }

    public function getProperty($name)
    {
        if (array_key_exists($name, $this->_properties)) {
            return $this->_properties[$name];
        }
        return null;
    }

    public function getProperties()
    {
        return $this->_properties;
    }

    public function getMainProperties()
    {
        return $this->_mainProperties;
    }

    public function getVersionProperties()
    {
        return $this->_versionProperties;
    }

    public function addProperty(BaseProperty $value)
    {
        $this->_properties[$value->getName()] = $value;
        if ($value->getPrimaryKey()) {
            $this->_mainIdProperty = $value;
        }
        return $this;
    }

    public function insertMain(Model $model, $db)
    {
        $values = array();
        foreach ($this->_mainProperties as $n => $p) {
            if ($p->getInsertable()) {
                $values[$n] = $p->toDbValue($model->getData($n));
            }
        }

        $result = $db->insert(new InsertStatement($this->_mainTable, $values));
        if ($this->_mainIdProperty->getAutoIncrement()) {
            $lid = $db->getLastInsertId();
            $model->setData($this->_mainIdProperty->getName(), $lid);
            $model->setData($this->_versionForeignProperty->getName(), $lid);
        }
        return $result;
    }

    public function insertVersion(Model $model, $db)
    {
        $values = array();
        foreach ($this->_versionProperties as $n => $p) {
            if ($p->getInsertable()) {
                $values[$n] = $p->toDbValue($model->getData($n));
            }
        }

        $result = $db->insert(new InsertStatement($this->_versionTable, $values));
        if ($this->_versionIdProperty->getAutoIncrement()) {
            $model->setData($this->_versionIdProperty->getName(), $db->getLastInsertId());
        }
        return $result;
    }

    public function updateMain(Model $model, $db)
    {
        $values = array();
        foreach ($this->_mainProperties as $n => $p) {
            if ($p->getUpdateable()) {
                $values[$n] = $p->toDbValue($model->getData($n));
            }
        }
        if (count($values) == 0) {
            return false;
        }
        $us = new UpdateStatement($this->_mainTable, $values);
        $us->eq($this->_mainIdProperty->getName(), $model->getData($this->_mainIdProperty->getName()));
        return $db->update($us);
    }

    public function updateVersion(Model $model, $db)
    {
        $values = array();
        foreach ($this->_versionProperties as $n => $p) {
            if ($p->getUpdateable()) {
                $values[$n] = $p->toDbValue($model->getData($n));
            }
        }
        if (count($values) == 0) {
            return false;
        }
        $us = new UpdateStatement($this->_versionTable, $values);
        $us->eq($this->_versionIdProperty->getName(), $model->getData($this->_versionIdProperty->getName()));
        return $db->update($us);
    }

    public function deleteMain(Model $model, $db)
    {
        $ds1 = new DeleteStatement($this->_versionTable);
        $ds1->eq($this->_versionForeignProperty->getName(), $model->getData($this->_versionForeignProperty->getName()));

        $ds2 = new DeleteStatement($this->_mainTable);
        $ds2->eq($this->_mainIdProperty->getName(), $model->getData($this->_mainIdProperty->getName()));

        $result = $db->delete($ds1);
        $result += $db->delete($ds2);

        return $result;
    }

    public function deleteByVersionId(Model $model, $db)
    {
        $ds1 = new DeleteStatement($this->_versionTable);
        $ds1->eq($this->_versionIdProperty->getName(), $model->getData($this->_versionIdProperty->getName()));

        $result = $db->delete($ds1);

        return $result;
    }

    public function deleteByVersionKey(Model $model, $db)
    {
        $ds1 = new DeleteStatement($this->_versionTable);
        $ds1->eq($this->_versionForeignProperty->getName(), $model->getData($this->_versionIdProperty->getName()))
            ->eq($this->_versionKeyProperty->getName(), $model->getData($this->_versionKeyProperty->getName()));

        $result = $db->delete($ds1);

        return $result;
    }

    public function validateProperties(Model $model)
    {
        $result = array();
        foreach ($this->_properties as $n => $p) {
            if (!$p->getAutoIncrement()) {
                $r = $p->validate($model->getData($n));
                if ($r !== true) {
                    $result[] = $n;
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
                $c = $this->findMain()
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

    public function findMain(){
        $fields = array();
        foreach ($this->_mainProperties as $prop) {
            $fields[] = $this->_mainTable . '.' . $prop->getName();
        }
        $result = new Query($this);
        return $result->select($fields)->from($this->_mainTable);
    }

    public function findVersion()
    {
        $fields = array();
        foreach ($this->_mainProperties as $prop) {
            $fields[] = $this->_mainTable . '.' . $prop->getName();
        }
        foreach ($this->_versionProperties as $prop) {
            $fields[] = $this->_versionTable . '.' . $prop->getName();
        }
        $result = new Query($this);
        return $result->select($fields)
            ->from($this->_mainTable)
            ->join($this->_versionTable,
                $this->_mainTable . '.' . $this->_mainIdProperty->getName(),
                $this->_versionTable . '.' . $this->_versionForeignProperty->getName());
    }

    public function newModel()
    {
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
        $r = new static($class, $metadata['table']);
        foreach ($metadata['properties'] as $p) {
            $r->addProperty($p);
        }

        foreach ($metadata['mainProperties'] as $n) {
            $r->_mainProperties[$n] = $r->getProperty($n);
        }
        foreach ($metadata['versionProperties'] as $n) {
            $r->_versionProperties[$n] = $r->getProperty($n);
        }

        if (array_key_exists('versionId', $metadata)) {
            $r->_versionIdProperty = $r->getProperty($metadata['versionId']);
        } else {
            $r->_versionIdProperty = $r->getProperty('version_id');
        }
        if (is_null($r->_versionIdProperty)) {
            $r->_versionIdProperty = IntegerProperty::create('version_id')->setAutoIncrement(true);
            $r->addProperty($r->_versionIdProperty);
        }

        if (array_key_exists('versionKey', $metadata)) {
            $r->_versionKeyProperty = $r->getProperty($metadata['versionKey']);
        } else {
            $r->_versionKeyProperty = $r->getProperty('version_key');
        }
        if (is_null($r->_versionKeyProperty)) {
            $r->_versionKeyProperty = IntegerProperty::create('version_key')->setNullable(false);
            $r->addProperty($r->_versionKeyProperty);
        }

        if (array_key_exists('foreignKey', $metadata)) {
            $r->_versionForeignProperty = $r->getProperty($metadata['foreignKey']);
        } else {
            $r->_versionForeignProperty = $r->getProperty('main_id');
        }
        if (is_null($r->_versionForeignProperty)) {
            $r->_versionForeignProperty = IntegerProperty::create('main_id')->setNullable(false);
            $r->addProperty($r->_versionForeignProperty);
        }

        if (!in_array($r->_mainIdProperty->getName(), $r->_mainProperties)) {
            $r->_mainProperties[$r->_mainIdProperty->getName()] = $r->_mainIdProperty;
        }
        if (!in_array($r->_versionIdProperty->getName(), $r->_versionProperties)) {
            $r->_versionProperties[$r->_versionIdProperty->getName()] = $r->_versionIdProperty;
        }
        if (!in_array($r->_versionKeyProperty->getName(), $r->_versionProperties)) {
            $r->_versionProperties[$r->_versionKeyProperty->getName()] = $r->_versionKeyProperty;
        }
        if (!in_array($r->_versionForeignProperty->getName(), $r->_versionProperties)) {
            $r->_versionProperties[$r->_versionForeignProperty->getName()] = $r->_versionForeignProperty;
        }

        self::$_entitys[$class] = $r;
        return $r;
    }

}
