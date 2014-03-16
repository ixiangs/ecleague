<?php
namespace Toy\Orm;

class SerizlizeProperty extends BaseProperty
{

    public function toDbValue($value)
    {
        return serialize($value);
    }

    public function fromDbValue($value)
    {
        return unserialize($value);
    }

    // public static function create($name){
    //     return new self($name);
    // }
}
