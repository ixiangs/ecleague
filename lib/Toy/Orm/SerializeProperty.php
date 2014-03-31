<?php
namespace Toy\Orm;

class SerializeProperty extends BaseProperty
{

    public function toDbValue($value)
    {
        return serialize($value);
    }

    public function fromDbValue($value)
    {
        return unserialize($value);
    }

    // static public function create($name){
    //     return new self($name);
    // }
}
