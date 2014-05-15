<?php
namespace Toy\Orm;

class Relation
{
    const TYPE_CHILD = 1;
    const TYPE_CHILDREN = 2;
    const TYPE_PARENT = 3;

    private $_thatModel = null;
    private $_thatProperty = null;
    private $_thisProperty = null;
    private $_propertyName = null;
    private $_type = null;

    public function __construct($type, $propertyName, $thatModel, $thatProperty, $thisProperty = null){
        $this->_type = $type;
        $this->_propertyName = $propertyName;
        $this->_thatModel = $thatModel;
        $this->_thatProperty = $thatProperty;
        $this->_thisProperty = $thisProperty;
    }

    public function getThatModel()
    {
        return $this->_thatModel;
    }

    public function setThatModel($value)
    {
        $this->_thatModel = $value;
        return $this;
    }

    public function getThatProperty()
    {
        return $this->_thatProperty;
    }

    public function setThatProperty($value)
    {
        $this->_thatProperty = $value;
        return $this;
    }

    public function getThisProperty()
    {
        return $this->_thisProperty;
    }

    public function setThisProperty($value)
    {
        $this->_thisProperty = $value;
        return $this;
    }

    public function getPropertyName()
    {
        return $this->_propertyName;
    }

    public function setPropertyName($value)
    {
        $this->_propertyName = $value;
        return $this;
    }

    public function getType()
    {
        return $this->_type;
    }

    public function setType($value)
    {
        $this->_type = $value;
        return $this;
    }

    static public function childRelation($propertyName, $thatModel, $thatProperty, $thisProperty = null){
        return new self(self::TYPE_CHILD, $thatModel, $thatProperty, $propertyName, $thisProperty);
    }

    static public function parentRelation($propertyName, $thisProperty, $thatModel, $thatProperty = null){
        return new self(self::TYPE_PARENT, $thatModel, $thatProperty, $propertyName, $thisProperty);
    }

    static public function childrenRelation($propertyName, $thatModel, $thatProperty, $thisProperty = null){
        return new self(self::TYPE_CHILDREN, $thatModel, $thatProperty, $propertyName, $thisProperty);
    }
}