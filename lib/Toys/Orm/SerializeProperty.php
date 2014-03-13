<?php
namespace Toys\Orm;

class SerizlizeProperty extends PropertyBase {

    public function toDbValue($value) {
        return serialize($value);
    }

    public function fromDbValue($value) {
        return unserialize($value);
    }

    // public static function create($name){
    //     return new self($name);
    // }
}
