<?php
namespace Toy\Orm;

class BooleanProperty extends BaseProperty
{

    public function toDbValue($value)
    {
        return empty($value) ? 0 : 1;
    }

    public function fromDbValue($value)
    {
        return empty($value) ? false : true;
    }
}
