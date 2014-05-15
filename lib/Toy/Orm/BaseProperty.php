<?php
namespace Toy\Orm;

abstract class BaseProperty extends \Toy\Object
{

    private $_name = null;
    private $_table = null;
    private $_nullable = true;
    private $_primaryKey = false;
    private $_unique = false;
    private $_autoIncrement = false;
    private $_defaultValue = null;
    private $_insertable = true;
    private $_updateable = true;
    private $_alwaysDirty = false;

    public function __construct($name)
    {
        $this->_name = $name;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function getTable()
    {
        return $this->_table;
    }

    public function setTable($value)
    {
        $this->_table = $value;
        return $this;
    }

    public function getNullable()
    {
        if ($this->_primaryKey || $this->_autoIncrement) {
            return false;
        }
        return $this->_nullable;
    }

    public function setNullable($value)
    {
        $this->_nullable = $value;
        return $this;
    }

    public function getPrimaryKey()
    {
        return $this->_primaryKey;
    }

    public function setPrimaryKey($value)
    {
        $this->_primaryKey = $value;
        return $this;
    }

    public function getUnique()
    {
        if ($this->_primaryKey || $this->_autoIncrement) {
            return true;
        }
        return $this->_unique;
    }

    public function setUnique($value)
    {
        $this->_unique = $value;
        return $this;
    }

    public function getAutoIncrement()
    {
        return $this->_autoIncrement;
    }

    public function setAutoIncrement($value)
    {
        $this->_autoIncrement = $value;
        return $this;
    }

    public function getDefaultValue()
    {
        return $this->_defaultValue;
    }

    public function setDefaultValue($value)
    {
        $this->_defaultValue = $value;
        return $this;
    }

    public function getInsertable()
    {
        if ($this->_autoIncrement) {
            return false;
        }

        return $this->_insertable;
    }

    public function setInsertable($value)
    {
        $this->_insertable = $value;
        return $this;
    }

    public function getUpdateable()
    {
        if ($this->_primaryKey) {
            return false;
        }
        if ($this->_autoIncrement) {
            return false;
        }
        return $this->_updateable;
    }

    public function setUpdateable($value)
    {
        $this->_updateable = $value;
        return $this;
    }

    public function getAlwaysDirty()
    {
        return $this->_alwaysDirty;
    }

    public function setAlwaysDirty($value)
    {
        $this->_alwaysDirty = $value;
        return $this;
    }

    public function toDbValue($value)
    {
        if (empty($value)) {
            return $this->_defaultValue;
        }
        return $value;
    }

    public function fromDbValue($value)
    {
        return $value;
    }

    public function validate($value)
    {
        return true;
    }

    static public function create($name)
    {
        return new static($name);
    }
}
