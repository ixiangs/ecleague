<?php
namespace Toy\Orm;


use Toy\Util\ArrayUtil;

class Metadata
{

    protected static $registered = array();

    protected $tableName = '';
    protected $properties = array();
    protected $relations = array();
    protected $primaryKey = null;

    private function __construct($tableName, $properties, $relations = array())
    {
        $this->tableName = $tableName;
        foreach ($properties as $property) {
            $this->properties[$property->getName()] = $property;
            if ($property->getPrimaryKey()) {
                $this->primaryKey = $property;
            }
        }

        foreach ($relations as $relation) {
            $this->relations[$relation->getPropertyName()] = $relation;
        }

    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    public function getAllProperties()
    {
        return $this->properties;
    }

    public function getInsertableProperties(){
        return ArrayUtil::filter($this->properties, function($property){
           return $property->getInsertable();
        });
    }

    public function getUpdatebaleProperties(){
        return ArrayUtil::filter($this->properties, function($property){
            return $property->getUpdateable();
        });
    }

    public function getProperty($name)
    {
        return $this->properties[$name];
    }

    public function hasProperty($name)
    {
        return array_key_exists($name, $this->properties);
    }

    public function addProperty($value)
    {
        $this->properties[$value->getName()] = $value;
        return $this;
    }

    public function getAllRelations()
    {
        return $this->relations;
    }

    public function getRelation($name)
    {
        return $this->relations[$name];
    }

    public function hasRelation($name)
    {
        return array_key_exists($name, $this->relations);
    }

    static public function get($class)
    {
        return array_key_exists($class, self::$registered) ? self::$registered[$class] : null;
    }

    static public function register($class, $tableName, $properties, $relations = array())
    {
        self::$registered[$class] = new self($tableName, $properties, $relations);
    }
}